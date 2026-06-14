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
        // Membuka query agar status NORMAL/AMAN bisa ikut lolos tampil di tabel
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

        // =======================================================
        // 🌟 SINKRONISASI THRESHOLD 11 SUNGAI (MAPPING SIMULASI)
        // =======================================================
        $aturanSungai = [
            'Sungai Gumbasa'   => ['BAHAYA' => [450, 520], 'SIAGA' => [350, 440], 'NORMAL' => [100, 240]],
            'Sungai Lariang'   => ['BAHAYA' => [600, 700], 'SIAGA' => [450, 590], 'NORMAL' => [150, 340]],
            'Sungai Lindu'     => ['BAHAYA' => [500, 580], 'SIAGA' => [390, 490], 'NORMAL' => [120, 290]],
            'Sungai Samba'     => ['BAHAYA' => [400, 480], 'SIAGA' => [300, 390], 'NORMAL' => [80, 190]],
            'Sungai Pakuli'    => ['BAHAYA' => [480, 560], 'SIAGA' => [360, 470], 'NORMAL' => [110, 250]],
            'Sungai Marawola'  => ['BAHAYA' => [420, 490], 'SIAGA' => [320, 410], 'NORMAL' => [90, 210]],
            'Sungai Palolo'    => ['BAHAYA' => [460, 530], 'SIAGA' => [340, 450], 'NORMAL' => [100, 230]],
            'Sungai Kulawi'    => ['BAHAYA' => [520, 610], 'SIAGA' => [400, 510], 'NORMAL' => [130, 290]],
            'Sungai Ngatabaru' => ['BAHAYA' => [430, 500], 'SIAGA' => [330, 420], 'NORMAL' => [95, 220]],
            'Sungai Wuno'      => ['BAHAYA' => [410, 480], 'SIAGA' => [310, 400], 'NORMAL' => [85, 200]],
            'Sungai Bangga'    => ['BAHAYA' => [470, 550], 'SIAGA' => [370, 460], 'NORMAL' => [105, 260]],
        ];

        $defaultBatas = ['BAHAYA' => [450, 550], 'SIAGA' => [300, 440], 'NORMAL' => [50, 240]];
        $batasSungai = $aturanSungai[$namaSungai] ?? $defaultBatas;

        // Normalisasi teks input status agar masuk ke key mapping
        if (in_array($status, ['WASPADA', 'SIAGA'])) {
            $status = 'SIAGA';
        } elseif (in_array($status, ['AWAS', 'MERAH', 'BAHAYA'])) {
            $status = 'BAHAYA';
        } else {
            $status = 'NORMAL';
        }

        $range = $batasSungai[$status];
        $nilaiCm = rand($range[0], $range[1]);
        // =======================================================

        // Format penanggalan disamakan murni (Waktu sistem uji tembak saat ini)
        $waktuLapangan = now()->timezone('Asia/Makassar')->format('d M Y');
        $waktuSistem = now()->timezone('Asia/Makassar')->format('d M Y, H:i') . ' WITA';

        // =======================================================
        // 🌟 SINKRONISASI LOGIKA FORMAT BARU (SIAGA, WASPADA, AWAS)
        // =======================================================
        if ($status === 'NORMAL') { // Status aman berubah jadi SIAGA
            $text = "🟢 *[SIAGA - " . $namaSungai . "]*\n\n";
            $text .= "Pemantauan berkala berjalan lancar. Kondisi debit aliran air sungai saat ini berada di bawah ambang batas aman.\n\n";
            $text .= "*DETAIL MONITORING:* \n";
            $text .= "• Ketinggian Air: " . $nilaiCm . " cm\n";
            $text .= "• Status Keamanan: *SIAGA*\n";
            $text .= "• Tanggal Rekaman Lapangan: " . $waktuLapangan . "\n";
            $text .= "• Waktu Broadcast Sistem: " . $waktuSistem . "\n";
        } elseif ($status === 'SIAGA') { // Status peringatan berubah jadi WASPADA
            $text = "🟡 *[WASPADA - " . $namaSungai . "]*\n\n";
            $text .= "Sistem mendeteksi adanya kenaikan volume air sungai melewati batas wajar pada titik pantau aktif.\n\n";
            $text .= "*DETAIL MONITORING:* \n";
            $text .= "• Ketinggian Air: " . $nilaiCm . " cm\n";
            $text .= "• Status Keamanan: *WASPADA*\n";
            $text .= "• Tanggal Rekaman Lapangan: " . $waktuLapangan . "\n";
            $text .= "• Waktu Broadcast Sistem: " . $waktuSistem . "\n\n";
            $text .= "⚠️ *HIMBAUAN KEAMANAN:* Warga yang beraktivitas di sekitar sempadan aliran diminta meningkatkan kewaspadaan dan mengamankan barang berharga.";
        } else { // Status darurat berubah jadi AWAS
            $text = "🔴 *[AWAS - " . $namaSungai . "]*\n\n";
            $text .= "Sistem mendeteksi lonjakan ekstrem debit air yang berpotensi kuat memicu luapan banjir besar di area pemukiman sekitar.\n\n";
            $text .= "*DETAIL EVALUASI SISTEM:* \n";
            $text .= "• Ketinggian Air: " . $nilaiCm . " cm\n";
            $text .= "• Status Keamanan: *AWAS*\n";
            $text .= "• Tanggal Rekaman Lapangan: " . $waktuLapangan . "\n";
            $text .= "• Waktu Broadcast Sistem: " . $waktuSistem . "\n\n";
            $text .= "🚨 *PERINTAH EVAKUASI:* Warga di sepanjang bantaran aliran diwajibkan segera mengungsi ke titik aman utama dan mengikuti instruksi tim evakuasi lapangan.";
        }

        try {
            $response = Http::withoutVerifying()->post("https://api.telegram.org/bot{$token}/sendMessage", [
                'chat_id' => $chatId,
                'text' => $text,
                'parse_mode' => 'Markdown',
            ]);

            if ($response->successful()) {
                // Catat log simulasi ke tabel VideoUploadLog agar sinkron masuk ke dashboard utama
                \App\Models\VideoUploadLog::create([
                    'nama_sungai' => $namaSungai,
                    'file_video' => 'bot_test_simulation.mp4',
                    'ukuran_file' => '0 MB',
                    'waktu_rekaman' => now()->toDateString(),
                    'nilai_level' => $nilaiCm,
                    'status_kondisi' => $status,
                    'keterangan' => 'Uji Coba Pengiriman Pesan Bot Telegram Berhasil.'
                ]);

                // Masukkan log ke tabel NotificationLog agar terdaftar di rekap log broadcast
                NotificationLog::create([
                    'message' => $text,
                    'status' => 'Terkirim ✅',
                ]);
                
                return redirect()->back()->with('success', 'Cek HP sekarang! Pesan simulasi Mori Nalove berhasil dikirim via Telegram.');
            } else {
                $errorReason = $response->json('description') ?? 'Gagal merespons API Telegram';
                return redirect()->back()->with('error', 'Gagal dari Telegram: ' . $errorReason);
            }

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error Koneksi Sistem: ' . $e->getMessage());
        }
    }
}