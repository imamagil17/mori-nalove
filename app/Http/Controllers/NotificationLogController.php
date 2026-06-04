<?php

namespace App\Http\Controllers;

use App\Models\NotificationLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class NotificationLogController extends Controller
{
    /**
     * Menampilkan halaman Log dengan filter dropdown sungai.
     * Mengizinkan status NORMAL tetap muncul di riwayat broadcast.
     */
    public function index(Request $request)
    {
        // 🌟 PERBAIKAN: Membuka query agar status NORMAL/AMAN bisa ikut lolos tampil di tabel
        $query = \App\Models\VideoUploadLog::query();
        
        // Filter interaktif berdasarkan pilihan dropdown sungai
        if ($request->filled('sungai')) {
            $query->where('nama_sungai', $request->sungai);
        }
        
        $logs = $query->latest()->get();
        return view('admin.notifications.index', compact('logs'));
    }

    /**
     * Memproses simulasi pengujian tombol "Uji Tembak Telegram"
     */
    public function testSend(Request $request)
    {
        $request->validate([
            'sungai' => 'required|string',
            'status' => 'required|string',
        ]);

        $token = env('TELEGRAM_BOT_TOKEN');
        $chatId = env('TELEGRAM_CHAT_ID');
        
        $namaSungai = $request->input('sungai', 'Sungai Gumbasa'); 
        $status = strtoupper($request->input('status', 'NORMAL'));

        // Kalkulasi simulasi angka Centimeter (cm) yang logis berdasarkan status
        $nilaiCm = 0;
        if ($status === 'SIAGA' || $status === 'WASPADA') { 
            $status = 'SIAGA';
            $nilaiCm = rand(250, 440); 
        } elseif ($status === 'BAHAYA' || $status === 'AWAS' || $status === 'MERAH') { 
            $status = 'BAHAYA';
            $nilaiCm = rand(450, 550); 
        } else { 
            $status = 'NORMAL'; 
            $nilaiCm = rand(50, 240); 
        }

        // Sinkronisasi waktu kejadian riil detik ini saat tombol diklik
        $waktu = now()->format('d M Y, H:i') . ' WITA';

        // 🌟 REVISI LOGIKA TEXT TELEGRAM AGAR TIDAK SALAH STATUS 🌟
        if ($status === 'NORMAL') {
            $text = "🤖 *[SISTEM MONITORING - " . $namaSungai . "]*\n\n";
            $text .= "Pemantauan berkala model YOLO & OpenCV berjalan lancar. Kondisi debit aliran air sungai saat ini berada di bawah ambang batas aman.\n\n";
            $text .= "• Nama Sungai: " . $namaSungai . "\n";
            $text .= "• Ketinggian Air: " . $nilaiCm . " cm\n";
            $text .= "• Status Keamanan: " . $status . "\n";
            $text .= "• Waktu Kejadian: " . $waktu . "\n";
        } else {
            $text = "🚨 *[DARURAT STATUS " . $status . " - WARNING BANJIR]*\n\n";
            $text .= "Sistem Flood Vision mendeteksi lonjakan debit air signifikan pada lokasi pemantauan aktif:\n\n";
            $text .= "• Nama Sungai: " . $namaSungai . "\n";
            $text .= "• Ketinggian Air: " . $nilaiCm . " cm\n";
            $text .= "• Status Keamanan: " . $status . "\n";
            $text .= "• Waktu Kejadian: " . $waktu . "\n\n";
            $text .= "*PERINTAH EVAKUASI:* Warga di sekitar aliran " . $namaSungai . " diharap tetap siaga dan bersiap melakukan evakuasi mandiri jika kondisi terus meningkat.";
        }

        try {
            $response = Http::withoutVerifying()->post("https://api.telegram.org/bot{$token}/sendMessage", [
                'chat_id' => $chatId,
                'text' => $text,
                'parse_mode' => 'Markdown',
            ]);

            if ($response->successful()) {
                // Catat log simulasi ke tabel VideoUploadLog agar sinkron masuk ke tabel bawah
                \App\Models\VideoUploadLog::create([
                    'nama_sungai' => $namaSungai,
                    'file_video' => 'bot_test_simulation.mp4',
                    'ukuran_file' => '0 MB',
                    'waktu_rekaman' => now(),
                    'nilai_level' => $nilaiCm,
                    'status_kondisi' => $status,
                    'keterangan' => 'Uji Coba Pengiriman Pesan Bot Telegram Berhasil.'
                ]);

                NotificationLog::create([
                    'message' => $text,
                    'status' => 'Terkirim ✅',
                ]);
                
                return redirect()->back()->with('success', 'Cek HP sekarang! Pesan bot terbaru berhasil dikirim ke Telegram.');
            } else {
                $errorReason = $response->json('description') ?? 'Gagal merespons API Telegram';
                return redirect()->back()->with('error', 'Gagal dari Telegram: ' . $errorReason);
            }

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error Koneksi Sistem: ' . $e->getMessage());
        }
    }
}