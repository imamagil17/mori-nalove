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
        // Ambil 15 data log terakhir, lalu urutkan kronologis (terlama ke terbaru)
        $logs = LogDeteksi::latest()->take(15)->get()->reverse()->values();

        return response()->json([
            'success' => true,
            'data' => $logs
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'status' => 'required|string',
            'nilai_level' => 'required|numeric',
            'sungai' => 'nullable|string',
        ]);

        // Simpan data log deteksi ke database
        $log = LogDeteksi::create([
            'status' => $request->status,
            'nilai_level' => $request->nilai_level,
        ]);

        // ── DATABASE ATURAN AMBANG BATAS DINAMIS PER SUNGAI ──
        $thresholds = [
            "Sungai Gumbasa" => [ 'waspada' => 250, 'siaga' => 350, 'bahaya' => 450, 'max' => 500 ],
            "Sungai Lindu"    => [ 'waspada' => 300, 'siaga' => 390, 'bahaya' => 500, 'max' => 550 ],
            "Sungai Lariang"  => [ 'waspada' => 400, 'siaga' => 550, 'bahaya' => 700, 'max' => 800 ],
            "Sungai Pakuli"   => [ 'waspada' => 200, 'siaga' => 300, 'bahaya' => 400, 'max' => 450 ],
            "Sungai Marawola" => [ 'waspada' => 180, 'siaga' => 270, 'bahaya' => 360, 'max' => 400 ],
            "Sungai Palolo"   => [ 'waspada' => 220, 'siaga' => 320, 'bahaya' => 420, 'max' => 460 ],
            "Sungai Kulawi"   => [ 'waspada' => 280, 'siaga' => 370, 'bahaya' => 470, 'max' => 520 ],
            "Sungai Ngatabaru"=> [ 'waspada' => 160, 'siaga' => 230, 'bahaya' => 300, 'max' => 340 ],
            "Sungai Wuno"     => [ 'waspada' => 170, 'siaga' => 250, 'bahaya' => 330, 'max' => 370 ],
            "Sungai Bangga"   => [ 'waspada' => 240, 'siaga' => 340, 'bahaya' => 440, 'max' => 480 ],
            "Sungai Samba"    => [ 'waspada' => 260, 'siaga' => 360, 'bahaya' => 460, 'max' => 500 ]
        ];

        $namaSungai = $request->input('sungai', 'Sungai Gumbasa');
        $levelPercentage = $request->nilai_level;
        $rules = $thresholds[$namaSungai] ?? [ 'waspada' => 200, 'siaga' => 300, 'bahaya' => 400, 'max' => 450 ];

        // Konversi persentase (%) level air Canny Edge menjadi satuan centimeter (cm) berdasarkan max scale
        $nilaiCm = round(($levelPercentage / 100) * $rules['max']);

        // Klasifikasikan status keamanan centimeter dinamis
        if ($nilaiCm >= $rules['bahaya']) {
            $status = 'BAHAYA';
        } elseif ($nilaiCm >= $rules['waspada']) {
            $status = 'SIAGA';
        } else {
            $status = 'NORMAL';
        }

        // ====================================================
        // --- MULAI KODE OTOMATISASI TELEGRAM ---
        // ====================================================
        $shouldSend = in_array($status, ['SIAGA', 'BAHAYA']);

        if ($shouldSend) {
            $token = env('TELEGRAM_BOT_TOKEN');
            $chatId = env('TELEGRAM_CHAT_ID');
            $waktu = now()->format('d M Y, H:i') . ' WITA';

            // Format isi pesan baru wajib seperti spesifikasi user
            $message = "🚨 *[DARURAT STATUS " . $status . " - WARNING BANJIR]*\n";
            $message .= "PERINGATAN KRITIS! Sistem Flood Vision mendeteksi luapan air pada lokasi pemantauan aktif.\n";
            $message .= "• Nama Sungai: " . $namaSungai . "\n";
            $message .= "• Ketinggian Air: " . $nilaiCm . " cm\n";
            $message .= "• Status Keamanan: " . $status . "\n";
            $message .= "• Waktu Kejadian: " . $waktu . "\n\n";
            $message .= "PERINTAH EVAKUASI: Dimohon kepada seluruh warga di sekitar aliran " . $namaSungai . " untuk tetap siaga dan bersiap evakuasi mandiri jika kondisi terus meningkat!";

            // Simulasikan juga log upload video agar datanya terikat di table riwayat visual admin
            try {
                \App\Models\VideoUploadLog::create([
                    'nama_sungai' => $namaSungai,
                    'file_video' => 'camera_feed_simulation.mp4',
                    'ukuran_file' => '0 MB',
                    'waktu_rekaman' => now(),
                    'nilai_level' => $nilaiCm,
                    'status_kondisi' => $status,
                    'keterangan' => 'Deteksi otomatis Canny Edge Camera aktif.'
                ]);
            } catch (\Exception $e) {
                // Ignore
            }

            try {
                $response = Http::withoutVerifying()->post("https://api.telegram.org/bot{$token}/sendMessage", [
                    'chat_id' => $chatId,
                    'text' => $message,
                    'parse_mode' => 'Markdown',
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
            'data' => $log,
            'simulated_cm' => $nilaiCm,
            'classified_status' => $status
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