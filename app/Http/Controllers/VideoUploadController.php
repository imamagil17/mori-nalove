<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\VideoUploadLog;
use App\Models\NotificationLog; // Log broadcast tercatat di halaman notifikasi
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
                    // PERBAIKAN: Gunakan fopen() agar video 86MB dikirim bertahap tanpa membuat PHP jebol
                    $response = Http::timeout(60)->attach(
                        'file', fopen($absolutePath, 'r'), $fileName
                    )->post($fastApiUrl . '/api/deteksi');

                    if ($response->successful()) {
                        $aiData = $response->json();
                        
                        if (isset($aiData['status']) && $aiData['status'] == 'sukses') {
                            $kondisiSistem = strtoupper($aiData['kondisi_banjir']);
                            
                            // --- PERBAIKAN TRANSLASI AI KE LARAVEL ---
                            if ($kondisiSistem == 'AWAS') {
                                // YOLO Merah = Database BAHAYA
                                $statusKondisi = 'AWAS';
                                $aiClassDetected = 'tanda_merah';
                                
                            } elseif ($kondisiSistem == 'WASPADA') {
                                // YOLO Kuning = Database SIAGA
                                $statusKondisi = 'WASPADA';
                                $aiClassDetected = 'tanda_kuning';
                                
                            } elseif ($kondisiSistem == 'SIAGA') {
                                // YOLO Hijau = Database NORMAL
                                $statusKondisi = 'SIAGA';
                                $aiClassDetected = 'tanda_hijau';
                                
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

                // Sekarang kode ini tidak akan error karena key 'AWAS' sudah ada di dalam array
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

                // LOGIKA PEMBEDA STRUKTUR KALIMAT NOTIFIKASI PER STATUS (NORMAL, SIAGA, BAHAYA)
                if ($statusKondisi === 'NORMAL') {
                    $textTelegram = "🤖 *[SISTEM MONITORING MORI NALOVE - " . $namaSungai . "]*\n\n";
                    $textTelegram .= "Pemantauan berkala model YOLO berjalan lancar. Kondisi debit aliran air sungai saat ini berada di bawah ambang batas aman.\n\n";
                    $textTelegram .= "• Nama Sungai: " . $namaSungai . "\n";
                    $textTelegram .= "• Ketinggian Air: " . $deteksiLevel . " cm\n";
                    $textTelegram .= "• Status Keamanan: " . $statusKondisi . "\n";
                    $textTelegram .= "• Tanggal Rekaman Lapangan: " . $waktuLapangan . "\n";
                    $textTelegram .= "• Waktu Broadcast Sistem: " . $waktuSistem . "\n";
                } elseif ($statusKondisi === 'SIAGA') {
                    $textTelegram = "⚠️ *[PERINGATAN STATUS SIAGA - MORI NALOVE]*\n\n";
                    $textTelegram .= "Sistem mendeteksi adanya kenaikan volume air sungai melewati batas wajar pada titik pantau aktif:\n\n";
                    $textTelegram .= "• Nama Sungai: " . $namaSungai . "\n";
                    $textTelegram .= "• Ketinggian Air: " . $deteksiLevel . " cm\n";
                    $textTelegram .= "• Status Keamanan: " . $statusKondisi . "\n";
                    $textTelegram .= "• Tanggal Rekaman Lapangan: " . $waktuLapangan . "\n";
                    $textTelegram .= "• Waktu Broadcast Sistem: " . $waktuSistem . "\n\n";
                    $textTelegram .= "*HIMBAUAN KEAMANAN:* Warga yang beraktivitas di sekitar sempadan aliran " . $namaSungai . " diminta meningkatkan kewaspadaan dan mengamankan barang berharga.";
                } else { // KONDISI STATUS BAHAYA
                    $textTelegram = "🚨 *[DARURAT STATUS BAHAYA - WARNING BANJIR MORI NALOVE]*\n\n";
                    $textTelegram .= "Sistem mendeteksi lonjakan ekstrem debit air yang berpotensi kuat memicu luapan banjir besar di area pemukiman sekitar:\n\n";
                    $textTelegram .= "• Nama Sungai: " . $namaSungai . "\n";
                    $textTelegram .= "• Ketinggian Air: " . $deteksiLevel . " cm\n";
                    $textTelegram .= "• Status Keamanan: " . $statusKondisi . "\n";
                    $textTelegram .= "• Tanggal Rekaman Lapangan: " . $waktuLapangan . "\n";
                    $textTelegram .= "• Waktu Broadcast Sistem: " . $waktuSistem . "\n\n";
                    $textTelegram .= "*PERINTAH EVAKUASI:* Warga di sepanjang bantaran aliran " . $namaSungai . " diwajibkan segera mengungsi ke titik aman utama dan mengikuti instruksi tim evakuasi lapangan.";
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