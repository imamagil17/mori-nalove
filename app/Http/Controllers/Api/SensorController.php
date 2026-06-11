<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\LogDeteksi; // Menggunakan LogDeteksi sebagai pusat data tunggal
use App\Models\NotificationLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class SensorController extends Controller
{
    public function store(Request $request)
    {
        // 1. Validasi data masuk dari sensor fisik/Python
        $request->validate([
            'water_level' => 'required|numeric',
            'sungai' => 'nullable|string'
        ]);

        $namaSungai = $request->input('sungai', 'Sungai Gumbasa');
        $levelPersentase = $request->water_level;
        $status = 'NORMAL';
        $shouldSend = false;

        // KONDISI KUNING (SIAGA POTENSI BANJIR)
        if ($levelPersentase >= 50 && $levelPersentase < 80) {
            $status = 'SIAGA';
            $shouldSend = true;
        } 
        // KONDISI MERAH (DARURAT EVAKUASI)
        elseif ($levelPersentase >= 80) {
            $status = 'BAHAYA';
            $shouldSend = true;
        }

        // 2. Simpan ke database tabel riwayat air terpusat (LogDeteksi)
        LogDeteksi::create([
            'nama_sungai' => $namaSungai,
            'status' => $status,
            'nilai_level' => $levelPersentase,
        ]);

        // 3. Setup Telegram jika status Siaga / Bahaya
        if ($shouldSend) {
            $token = env('TELEGRAM_BOT_TOKEN');
            $chatId = env('TELEGRAM_CHAT_ID');
            
            $message = $status === 'BAHAYA' 
                ? "🚨 [DARURAT STATUS MERAH - BAHAYA BANJIR]\nPERINGATAN KRITIS! Air sungai $namaSungai telah melewati batas aman.\n\n• Ketinggian Air: $levelPersentase%\n• Status: BAHAYA\n\n❗ PERINTAH EVAKUASI: Dimohon kepada seluruh warga di area terdampak untuk SEGERA MENGUNGSI!"
                : "⚠️ [PERINGATAN STATUS KUNING - SIAGA]\nSistem mendeteksi kenaikan air sungai $namaSungai.\n\n• Ketinggian Air: $levelPersentase%\n• Status: SIAGA\n\n📢 Warga dihimbau untuk tetap waspada.";

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

        // Balasan respon balik ke Python/IoT
        return response()->json([
            'status' => 'sukses',
            'dikirim_ke_telegram' => $shouldSend
        ], 200);
    }
}