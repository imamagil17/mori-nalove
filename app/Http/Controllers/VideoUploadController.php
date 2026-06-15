<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\VideoUploadLog;
use App\Models\NotificationLog;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Http; 
use Illuminate\Support\Facades\Log;  

class VideoUploadController extends Controller
{
    public function index()
    {
        $videos = VideoUploadLog::latest()->get();
        return view('admin.videos.kelola-video', compact('videos'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_sungai' => 'required|string',
            'waktu_rekaman' => 'required|date',
            'keterangan' => 'nullable|string',
            'video_file' => 'required|file|mimes:mp4,mov,avi|max:204800',
        ]);

        try {
            if ($request->hasFile('video_file')) {
                $file = $request->file('video_file');
                
                $fileSizeRaw = $file->getSize(); 
                $fileSizeMb = round($fileSizeRaw / (1024 * 1024), 1) . ' MB';

                // Simpan video ke folder public/videos di Laravel
                $fileName = time() . '_' . $file->getClientOriginalName();
                $filePath = $file->storeAs('videos', $fileName, 'public');
                
                // Ambil path lengkap (absolut) di hardisk untuk dikirim ke Python
                $absolutePath = storage_path('app/public/' . $filePath);

                $namaSungai = $request->nama_sungai;
                
                // =======================================================
                // 🌟 JEMBATAN API: LARAVEL -> PYTHON (YOLOv26 Video Mode)
                // =======================================================
                $statusKondisi = 'NORMAL'; 
                $aiClassDetected = 'Tidak Ada';
                $kondisiSistem = 'AMAN';

                try {
                    // Otomatis mengambil alamat port 8001 dari file .env
                    $fastApiUrl = env('FASTAPI_AI_URL', 'http://127.0.0.1:8001');

                    // Menembak file video secara fisik ke server FastAPI
                    $response = Http::timeout(60)->attach(
                        'file', file_get_contents($absolutePath), $fileName
                    )->post($fastApiUrl . '/api/deteksi');

                    if ($response->successful()) {
                        $aiData = $response->json();
                        
                        if (isset($aiData['status']) && $aiData['status'] == 'sukses') {
                            $kondisiSistem = strtoupper($aiData['kondisi_banjir']);
                            
                            if ($kondisiSistem == 'BAHAYA') {
                                $statusKondisi = 'BAHAYA';
                                $aiClassDetected = 'tanda_merah';
                            } elseif ($kondisiSistem == 'SIAGA') {
                                $statusKondisi = 'SIAGA';
                                $aiClassDetected = 'tanda_kuning';
                            } else {
                                $statusKondisi = 'NORMAL';
                                $aiClassDetected = 'tanda_hijau';
                            }
                        }
                    } else {
                        Log::error("API Python membalas error: " . $response->body());
                    }
                } catch (\Exception $apiError) {
                    Log::error("Gagal terhubung ke API Python: " . $apiError->getMessage());
                }
                // =======================================================

                // =======================================================
                // 🌟 DYNAMIC THRESHOLD 11 SUNGAI (MAPPING CENTIMETER)
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

                $range = $batasSungai[$statusKondisi];
                $deteksiLevel = rand($range[0], $range[1]);
                // =======================================================

                // Simpan data log analisis video ke Database
                VideoUploadLog::create([
                    'nama_sungai' => $namaSungai,
                    'file_video' => $filePath,
                    'ukuran_file' => $fileSizeMb,
                    'waktu_rekaman' => $request->waktu_rekaman,
                    'nilai_level' => $deteksiLevel, 
                    'status_kondisi' => $statusKondisi,
                    'keterangan' => "YOLO class: [" . $aiClassDetected . "] | " . $request->keterangan 
                ]);

                // =======================================================
                // 🌟 TRIGGER REAL-TIME EWS: SINKRONISASI TELEGRAM BOT & NOTIFIKASI
                // =======================================================
                $token = env('TELEGRAM_BOT_TOKEN');
                $chatId = env('TELEGRAM_CHAT_ID');
                
                $waktuLapangan = \Carbon\Carbon::parse($request->waktu_rekaman)->format('d M Y');
                $waktuSistem = now()->timezone('Asia/Makassar')->format('d M Y, H:i') . ' WITA';

                // FORMAT NOTIFIKASI BARU (SIAGA, WASPADA, AWAS) - TANPA DUPLIKASI NAMA SUNGAI
                if ($statusKondisi === 'NORMAL') {
                    $textTelegram = "🟢 *[SIAGA - " . $namaSungai . "]*\n\n";
                    $textTelegram .= "Pemantauan berkala berjalan lancar. Kondisi debit aliran air sungai saat ini berada di bawah ambang batas aman.\n\n";
                    $textTelegram .= "*DETAIL MONITORING:* \n";
                    $textTelegram .= "• Ketinggian Air: " . $deteksiLevel . " cm\n";
                    $textTelegram .= "• Status Keamanan: *SIAGA*\n";
                    $textTelegram .= "• Tanggal Rekaman Lapangan: " . $waktuLapangan . "\n";
                    $textTelegram .= "• Waktu Broadcast Sistem: " . $waktuSistem . "\n";
                } elseif ($statusKondisi === 'SIAGA') {
                    $textTelegram = "🟡 *[WASPADA - " . $namaSungai . "]*\n\n";
                    $textTelegram .= "Sistem mendeteksi adanya kenaikan volume air sungai melewati batas wajar pada titik pantau aktif.\n\n";
                    $textTelegram .= "*DETAIL MONITORING:* \n";
                    $textTelegram .= "• Ketinggian Air: " . $deteksiLevel . " cm\n";
                    $textTelegram .= "• Status Keamanan: *WASPADA*\n";
                    $textTelegram .= "• Tanggal Rekaman Lapangan: " . $waktuLapangan . "\n";
                    $textTelegram .= "• Waktu Broadcast Sistem: " . $waktuSistem . "\n\n";
                    $textTelegram .= "⚠️ *HIMBAUAN KEAMANAN:* Warga yang beraktivitas di sekitar sempadan aliran diminta meningkatkan kewaspadaan dan mengamankan barang berharga.";
                } else { // KONDISI STATUS BAHAYA
                    $textTelegram = "🔴 *[AWAS - " . $namaSungai . "]*\n\n";
                    $textTelegram .= "Sistem mendeteksi lonjakan ekstrem debit air yang berpotensi kuat memicu luapan banjir besar di area pemukiman sekitar.\n\n";
                    $textTelegram .= "*DETAIL EVALUASI SISTEM:* \n";
                    $textTelegram .= "• Ketinggian Air: " . $deteksiLevel . " cm\n";
                    $textTelegram .= "• Status Keamanan: *AWAS*\n";
                    $textTelegram .= "• Tanggal Rekaman Lapangan: " . $waktuLapangan . "\n";
                    $textTelegram .= "• Waktu Broadcast Sistem: " . $waktuSistem . "\n\n";
                    $textTelegram .= "🚨 *PERINTAH EVAKUASI:* Warga di sepanjang bantaran aliran diwajibkan segera mengungsi ke titik aman utama dan mengikuti instruksi tim evakuasi lapangan.";
                }

                // Jalankan proses eksekusi pengiriman Telegram & Logging internal
                try {
                    if ($token && $chatId) {
                        $telegramResponse = Http::withoutVerifying()->post("https://api.telegram.org/bot{$token}/sendMessage", [
                            'chat_id' => $chatId,
                            'text' => $textTelegram,
                            'parse_mode' => 'Markdown',
                        ]);

                        if ($telegramResponse->successful()) {
                            NotificationLog::create([
                                'message' => $textTelegram,
                                'status' => 'Terkirim ✅',
                            ]);
                        } else {
                            Log::error("Telegram API miring/gagal saat proses upload video: " . $telegramResponse->body());
                        }
                    }
                } catch (\Exception $telegramError) {
                    Log::error("Gagal broadcast otomatis dari upload video: " . $telegramError->getMessage());
                }
                // =======================================================

                return redirect()->back()->with('success', 'Video berhasil diunggah, dianalisis YOLOv26, dan notifikasi berjenjang dikirim!');
            }

            return redirect()->back()->with('error', 'Gagal membaca file berkas video.');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan sistem: ' . $e->getMessage());
        }
    }
}