<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\WaterLevelLog;
use App\Models\NotificationLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class SensorController extends Controller
{
    public function store(Request $request)
    {
        // 1. Validasi data masuk dari Python
        $request->validate([
            'water_level' => 'required|numeric',
        ]);

        // 2. Simpan ke database tabel riwayat air
        $log = new WaterLevelLog();
        $log->water_level = $request->water_level;
        $log->save();

        // 3. Setup Telegram
        $token = env('TELEGRAM_BOT_TOKEN');
        $chatId = env('TELEGRAM_CHAT_ID');
        $message = "";
        $shouldSend = false;

        // KONDISI KUNING (SIAGA POTENSI BANJIR)
        if ($request->water_level >= 50 && $request->water_level < 80) {
            $message = "⚠️ [PERINGATAN STATUS KUNING - SIAGA]\n";
            $message .= "Sistem Flood-Vision mendeteksi kenaikan air sungai.\n\n";
            $message .= "• Ketinggian Air: " . $request->water_level . "%\n";
            $message .= "• Status Keamanan: SIAGA (KUNING)\n";
            $message .= "• Waktu Kejadian: " . now()->format('d M Y, H:i') . " WITA\n\n";
            $message .= "📢 PEMBERITAHUAN: Terdapat POTENSI BANJIR di sekitar bantaran sungai. Dihimbau kepada seluruh warga untuk tetap waspada, memantau situasi, dan mulai mengamankan barang-barang berharga.";
            $shouldSend = true;
        } 
        // KONDISI MERAH (DARURAT EVAKUASI)
        elseif ($request->water_level >= 80) {
            $message = "🚨 [DARURAT STATUS MERAH - BAHAYA BANJIR]\n";
            $message .= "PERINGATAN KRITIS! Air sungai telah melewati batas aman.\n\n";
            $message .= "• Ketinggian Air: " . $request->water_level . "%\n";
            $message .= "• Status Keamanan: BAHAYA (MERAH)\n";
            $message .= "• Waktu Kejadian: " . now()->format('d M Y, H:i') . " WITA\n\n";
            $message .= "❗ PERINTAH EVAKUASI: Banjir luapan besar berpotensi terjadi saat ini juga. Dimohon kepada seluruh warga di area terdampak untuk SEGERA MENINGGALKAN RUMAH DAN MENGUNGSI ke titik aman atau posko darurat sekarang!";
            $shouldSend = true;
        }

        // 4. Eksekusi Tembak Telegram jika status Kuning / Merah
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

        // Balasan respon balik ke Python
        return response()->json([
            'status' => 'sukses',
            'dikirim_ke_telegram' => $shouldSend
        ], 200);
    }
}