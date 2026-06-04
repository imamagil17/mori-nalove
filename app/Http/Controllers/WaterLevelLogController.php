<?php

namespace App\Http\Controllers;

use App\Models\WaterLevelLog;
use App\Models\NotificationLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class WaterLevelLogController extends Controller
{
    public function index()
    {
        $logs = WaterLevelLog::latest()->paginate(15);
        return view('admin.water_logs.index', compact('logs'));
    }

    public function create()
    {
        return view('admin.water_logs.create');
    }

    public function store(Request $request)
    {
        // 1. Validasi input (berlaku untuk manual maupun otomatis dari JavaScript)
        $request->validate([
            'water_level' => 'required|numeric|min:0|max:100',
            'foto_visual' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        // 2. Simpan data ketinggian air ke database
        $log = new WaterLevelLog();
        $log->water_level = $request->water_level;

        // Proses unggah file gambar visual jika ada
        if ($request->hasFile('foto_visual')) {
            $file = $request->file('foto_visual');
            $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            // Menyimpan ke public/water_logs/
            $file->move(public_path('water_logs'), $filename);
            // Simpan path ke database
            $log->foto_visual = 'water_logs/' . $filename;
        }

        $log->save();

        // 3. Setup Token & Chat ID Telegram
        $token = env('TELEGRAM_BOT_TOKEN');
        $chatId = env('TELEGRAM_CHAT_ID');
        
        $message = "";
        $shouldSend = false;

        // KONDISI 1: STATUS KUNING (Rentang 50% sampai 79.9%)
        if ($request->water_level >= 50 && $request->water_level < 80) {
            $message = "⚠️ [PERINGATAN STATUS KUNING - SIAGA]\n";
            $message .= "Sistem Mitigasi Banjir Cerdas mendeteksi kenaikan air sungai.\n\n";
            $message .= "• Ketinggian Air: " . $request->water_level . "%\n";
            $message .= "• Status Keamanan: SIAGA (KUNING)\n";
            $message .= "• Waktu Kejadian: " . now()->format('d M Y, H:i') . " WITA\n\n";
            $message .= "📢 PEMBERITAHUAN: Terdapat POTENSI BANJIR di sekitar bantaran sungai. Dihimbau kepada seluruh warga untuk tetap waspada, memantau situasi, dan mulai mengamankan barang-barang berharga.";
            
            $shouldSend = true;
        } 
        // KONDISI 2: STATUS MERAH (Rentang 80% sampai 100%)
        elseif ($request->water_level >= 80) {
            $message = "🚨 [DARURAT STATUS MERAH - BAHAYA BANJIR]\n";
            $message .= "PERINGATAN KRITIS! Air sungai telah melewati batas aman.\n\n";
            $message .= "• Ketinggian Air: " . $request->water_level . "%\n";
            $message .= "• Status Keamanan: BAHAYA (MERAH)\n";
            $message .= "• Waktu Kejadian: " . now()->format('d M Y, H:i') . " WITA\n\n";
            $message .= "❗ PERINTAH EVAKUASI: Banjir luapan besar berpotensi terjadi saat ini juga. Dimohon kepada seluruh warga di area terdampak untuk SEGERA MENINGGALKAN RUMAH DAN MENGUNGSI ke titik aman atau posko darurat sekarang!";
            
            $shouldSend = true;
        }

        // 4. Proses Eksekusi Kirim ke Telegram jika kondisi terpenuhi
        if ($shouldSend) {
            try {
                $response = Http::withoutVerifying()->post("https://api.telegram.org/bot{$token}/sendMessage", [
                    'chat_id' => $chatId,
                    'text' => $message,
                ]);

                // Catat log notifikasi ke database
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

        // KUNCI OTOMATISASI: Jika data dikirim lewat JavaScript (AJAX) di Dashboard
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'status' => 'success',
                'message' => 'Data otomatis dari kamera berhasil disimpan dan dicek!',
                'telegram_sent' => $shouldSend
            ], 200);
        }

        // Jika input manual lewat form, tetap lakukan redirect biasa
        return redirect()->route('admin.water_logs.index')->with('success', 'Data ketinggian air berhasil disimpan!');
    }
}
