<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LogDeteksi;
use App\Models\NotificationLog; 
use Illuminate\Support\Facades\Http; 

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

        // ── DATABASE ATURAN AMBANG BATAS DINAMIS PER SUNGAI ──
        $thresholds = [
            "Sungai Gumbasa" => [ 'siaga' => 250, 'waspada' => 350, 'awas' => 450, 'max' => 500 ],
            "Sungai Lindu"    => [ 'siaga' => 300, 'waspada' => 390, 'awas' => 500, 'max' => 550 ],
            "Sungai Lariang"  => [ 'siaga' => 400, 'waspada' => 550, 'awas' => 700, 'max' => 800 ],
            "Sungai Pakuli"   => [ 'siaga' => 200, 'waspada' => 300, 'awas' => 400, 'max' => 450 ],
            "Sungai Marawola" => [ 'siaga' => 180, 'waspada' => 270, 'awas' => 360, 'max' => 400 ],
            "Sungai Palolo"   => [ 'siaga' => 220, 'waspada' => 320, 'awas' => 420, 'max' => 460 ],
            "Sungai Kulawi"   => [ 'siaga' => 280, 'waspada' => 370, 'awas' => 470, 'max' => 520 ],
            "Sungai Ngatabaru"=> [ 'siaga' => 160, 'waspada' => 230, 'awas' => 300, 'max' => 340 ],
            "Sungai Wuno"     => [ 'siaga' => 170, 'waspada' => 250, 'awas' => 330, 'max' => 370 ],
            "Sungai Bangga"   => [ 'siaga' => 240, 'waspada' => 340, 'awas' => 440, 'max' => 480 ],
            "Sungai Samba"    => [ 'siaga' => 260, 'waspada' => 360, 'awas' => 460, 'max' => 500 ]
        ];

        $namaSungai = $request->input('sungai', 'Sungai Gumbasa');
        $levelPercentage = $request->nilai_level;
        $rules = $thresholds[$namaSungai] ?? [ 'siaga' => 200, 'waspada' => 300, 'awas' => 400, 'max' => 450 ];

        // Konversi persentase (%) level air Canny Edge menjadi satuan centimeter (cm) berdasarkan max scale
        $nilaiCm = round(($levelPercentage / 100) * $rules['max']);

        // Klasifikasikan status keamanan centimeter dinamis
        if ($nilaiCm >= $rules['awas']) {
            $status = 'AWAS';
        } elseif ($nilaiCm >= $rules['waspada']) {
            $status = 'WASPADA';
        } elseif ($nilaiCm >= $rules['siaga']) {
            $status = 'SIAGA';
        } else {
            $status = 'NORMAL';
        }

        // ====================================================
        // --- PERBAIKAN: SIMPAN LOG DETEKSI SETELAH DIKONVERSI ---
        // ====================================================
        $log = LogDeteksi::create([
            'nama_sungai' => $namaSungai,
            'status'      => strtoupper($status), // Normalisasi string huruf besar
            'nilai_level' => $nilaiCm,            // Disimpan sebagai CM untuk konsistensi analitik
        ]);

        // ====================================================
        // --- MULAI KODE OTOMATISASI TELEGRAM ---
        // ====================================================
        $shouldSend = in_array($status, ['siaga', 'waspada', 'awas']);

        if ($shouldSend) {
            $token = env('TELEGRAM_BOT_TOKEN');
            $chatId = env('TELEGRAM_CHAT_ID');
            $waktu = now()->format('d M Y, H:i') . ' WITA';

            // Format isi pesan baru wajib seperti spesifikasi user
            $message = "🚨 *[DARURAT STATUS " . $status . " - WARNING BANJIR]*\n";
            $message .= "PERINGATAN KRITIS! Sistem Mori Nalove mendeteksi luapan air pada lokasi pemantauan aktif.\n";
            $message .= "• Nama Sungai: " . $namaSungai . "\n";
            $message .= "• Ketinggian Air: " . $nilaiCm . " cm\n";
            $message .= "• Status Keamanan: " . $status . "\n";
            $message .= "• Waktu Kejadian: " . $waktu . "\n\n";
            $message .= "PERINTAH EVAKUASI: Dimohon kepada seluruh warga di sekitar aliran " . $namaSungai . " untuk tetap waspada dan bersiap evakuasi mandiri jika kondisi terus meningkat!";

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
    // --- FETCH NOTIFIKASI UNTUK MADING ---
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