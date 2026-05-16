<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LogDeteksi;
use App\Models\NotificationLog; // Wajib ditambahkan untuk catat riwayat notif
use Illuminate\Support\Facades\Http; // Wajib ditambahkan untuk nembak API Telegram

class LogController extends Controller
{
    public function index()
    {
        // Get the latest 20 logs, then sort them chronologically (oldest to newest)
        $logs = LogDeteksi::latest()->take(20)->get()->reverse()->values();

        return response()->json([
            'message' => 'Success',
            'data' => $logs
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'status' => 'required|string',
            'nilai_level' => 'required|numeric',
        ]);

        $log = LogDeteksi::create($validated);

        // ====================================================
        // --- MULAI KODE OTOMATISASI TELEGRAM ---
        // ====================================================
        $token = env('TELEGRAM_BOT_TOKEN');
        $chatId = env('TELEGRAM_CHAT_ID');
        $message = "";
        $shouldSend = false;

        $status_kamera = strtoupper($request->status);
        $level = $request->nilai_level;

        // KONDISI 1: SIAGA (Kuning)
        if ($status_kamera === 'SIAGA') {
            $message = "⚠️ [PERINGATAN STATUS KUNING - SIAGA]\n";
            $message .= "Sistem Flood-Vision mendeteksi kenaikan air sungai.\n\n";
            $message .= "• Ketinggian Air: " . $level . "%\n";
            $message .= "• Status Keamanan: SIAGA\n";
            $message .= "• Waktu Kejadian: " . now()->format('d M Y, H:i') . " WITA\n\n";
            $message .= "📢 PEMBERITAHUAN: Terdapat POTENSI BANJIR di sekitar bantaran sungai. Dihimbau kepada seluruh warga untuk tetap waspada.";
            $shouldSend = true;
        } 
        // KONDISI 2: AWAS (Merah)
        elseif ($status_kamera === 'AWAS') {
            $message = "🚨 [DARURAT STATUS MERAH - AWAS BANJIR]\n";
            $message .= "PERINGATAN KRITIS! Air sungai telah melewati batas aman.\n\n";
            $message .= "• Ketinggian Air: " . $level . "%\n";
            $message .= "• Status Keamanan: AWAS\n";
            $message .= "• Waktu Kejadian: " . now()->format('d M Y, H:i') . " WITA\n\n";
            $message .= "❗ PERINTAH EVAKUASI: Banjir luapan besar berpotensi terjadi saat ini juga. Dimohon kepada seluruh warga terdampak untuk SEGERA MENGUNGSI ke titik aman!";
            $shouldSend = true;
        }

        // Eksekusi Kirim jika kondisi Siaga/Awas
        if ($shouldSend) {
            try {
                $response = Http::withoutVerifying()->post("https://api.telegram.org/bot{$token}/sendMessage", [
                    'chat_id' => $chatId,
                    'text' => $message,
                ]);

                NotificationLog::create([
                    'message' => $message,
                    'status' => $response->successful() ? 'Terkirim ✅' : 'Gagal ❌',
                ]);
            } catch (\Exception $e) {
                NotificationLog::create([
                    'message' => $message,
                    'status' => 'Gagal ❌ (Error Sistem)',
                ]);
            }
        }
        // ====================================================
        // --- BATAS AKHIR KODE TELEGRAM ---
        // ====================================================

        return response()->json([
            'message' => 'Log saved successfully',
            'data' => $log
        ], 201);
    }

    // ====================================================
    // --- TAMBAHAN BARU: FETCH NOTIFIKASI UNTUK MADING ---
    // ====================================================
    public function notifications()
    {
        // Mengambil 3 log notifikasi peringatan terakhir dari database
        $notifs = NotificationLog::latest()->take(3)->get();
        
        return response()->json([
            'success' => true,
            'data' => $notifs
        ]);
    }
}   