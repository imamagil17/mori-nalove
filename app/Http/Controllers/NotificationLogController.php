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

        $aturanSungai = [
            'Sungai Gumbasa'   => ['AWAS' => [450, 520], 'WASPADA' => [350, 449], 'SIAGA' => [150, 349], 'NORMAL' => [50, 149]],
            'Sungai Lariang'   => ['AWAS' => [600, 700], 'WASPADA' => [450, 599], 'SIAGA' => [250, 449], 'NORMAL' => [50, 249]],
            'Sungai Lindu'     => ['AWAS' => [500, 580], 'WASPADA' => [390, 499], 'SIAGA' => [190, 389], 'NORMAL' => [50, 189]],
            'Sungai Samba'     => ['AWAS' => [400, 480], 'WASPADA' => [300, 399], 'SIAGA' => [100, 299], 'NORMAL' => [50, 99]],
            'Sungai Pakuli'    => ['AWAS' => [480, 560], 'WASPADA' => [360, 479], 'SIAGA' => [160, 359], 'NORMAL' => [50, 159]],
            'Sungai Marawola'  => ['AWAS' => [420, 490], 'WASPADA' => [320, 419], 'SIAGA' => [120, 319], 'NORMAL' => [50, 119]],
            'Sungai Palolo'    => ['AWAS' => [460, 530], 'WASPADA' => [340, 459], 'SIAGA' => [140, 339], 'NORMAL' => [50, 139]],
            'Sungai Kulawi'    => ['AWAS' => [520, 610], 'WASPADA' => [400, 519], 'SIAGA' => [200, 399], 'NORMAL' => [50, 199]],
            'Sungai Ngatabaru' => ['AWAS' => [430, 500], 'WASPADA' => [330, 429], 'SIAGA' => [130, 329], 'NORMAL' => [50, 129]],
            'Sungai Wuno'      => ['AWAS' => [410, 480], 'WASPADA' => [310, 409], 'SIAGA' => [110, 309], 'NORMAL' => [50, 109]],
            'Sungai Bangga'    => ['AWAS' => [470, 550], 'WASPADA' => [370, 469], 'SIAGA' => [170, 369], 'NORMAL' => [50, 169]],
        ];

        $defaultBatas = ['AWAS' => [450, 550], 'WASPADA' => [350, 449], 'SIAGA' => [250, 349], 'NORMAL' => [50, 249]];
        $batasSungai = $aturanSungai[$namaSungai] ?? $defaultBatas;

        // Pastikan status yang diketik masuk ke dalam key yang benar
        if (!in_array($status, ['AWAS', 'WASPADA', 'SIAGA', 'NORMAL'])) {
            $status = 'NORMAL';
        }

        $range = $batasSungai[$status];
        $nilaiCm = rand($range[0], $range[1]);

        $waktuLapangan = now()->timezone('Asia/Makassar')->format('d M Y, H:i') . ' WITA';
        $waktuSistem = now()->timezone('Asia/Makassar')->format('d M Y, H:i') . ' WITA';

        // PENYERAGAMAN TEKS TELEGRAM 4 LEVEL (Sama persis dengan VideoUpload)
        if ($status === 'NORMAL') {
            $text = "🟢 *[SIMULASI UJI TEMBAK - " . $namaSungai . "]*\n\n";
            $text .= "Pemantauan model YOLO berjalan lancar. Debit aliran air sungai berada di bawah ambang batas aman.\n\n";
            $text .= "• Ketinggian Air: " . $nilaiCm . " cm\n";
            $text .= "• Status Keamanan: NORMAL\n";
            $text .= "• Waktu Rekaman: " . $waktuLapangan . "\n";
            $text .= "• Waktu Broadcast: " . $waktuSistem;

        } elseif ($status === 'SIAGA') {
            $text = "🟡 *[SIMULASI UJI TEMBAK: SIAGA - MORI NALOVE]*\n\n";
            $text .= "Sistem mendeteksi adanya kenaikan volume air sungai. Harap pantau aktivitas sekitar:\n\n";
            $text .= "• Ketinggian Air: " . $nilaiCm . " cm\n";
            $text .= "• Status Keamanan: SIAGA\n";
            $text .= "• Waktu Rekaman: " . $waktuLapangan . "\n";
            $text .= "• Waktu Broadcast: " . $waktuSistem . "\n\n";
            $text .= "*HIMBAUAN:* Kurangi aktivitas di sekitar sempadan aliran sungai.";

        } elseif ($status === 'WASPADA') {
            $text = "🟠 *[SIMULASI UJI TEMBAK: WASPADA - MORI NALOVE]*\n\n";
            $text .= "Sistem mendeteksi debit air terus meningkat dan MENDEKATI BATAS BAHAYA:\n\n";
            $text .= "• Ketinggian Air: " . $nilaiCm . " cm\n";
            $text .= "• Status Keamanan: WASPADA\n";
            $text .= "• Waktu Rekaman: " . $waktuLapangan . "\n";
            $text .= "• Waktu Broadcast: " . $waktuSistem . "\n\n";
            $text .= "*PERHATIAN:* Warga diharapkan mengamankan barang berharga ke tempat tinggi dan bersiap evakuasi.";

        } else { 
            $text = "🚨 *[SIMULASI UJI TEMBAK: AWAS - WARNING BANJIR]*\n\n";
            $text .= "Sistem mendeteksi lonjakan ekstrem debit air yang berpotensi kuat memicu LUAPAN BANJIR:\n\n";
            $text .= "• Ketinggian Air: " . $nilaiCm . " cm\n";
            $text .= "• Status Keamanan: AWAS\n";
            $text .= "• Waktu Rekaman: " . $waktuLapangan . "\n";
            $text .= "• Waktu Broadcast: " . $waktuSistem . "\n\n";
            $text .= "*PERINTAH EVAKUASI:* Warga di bantaran aliran " . $namaSungai . " DIWAJIBKAN segera mengungsi ke titik aman!";
        }

        try {
            $response = Http::withoutVerifying()->post("https://api.telegram.org/bot{$token}/sendMessage", [
                'chat_id' => $chatId,
                'text' => $text,
                'parse_mode' => 'Markdown',
            ]);

            if ($response->successful()) {
                NotificationLog::create([
                    'message' => $text,
                    'status' => 'Terkirim ✅',
                ]);
                return redirect()->back()->with('success', 'Pesan simulasi Mori Nalove berhasil dikirim via Telegram.');
            } else {
                return redirect()->back()->with('error', 'Gagal dari Telegram: ' . ($response->json('description') ?? 'Gagal respons'));
            }

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error Koneksi Sistem: ' . $e->getMessage());
        }
    }
}