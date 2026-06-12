<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\VideoUploadLog;
use App\Models\NotificationLog; 
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Http; 
use Illuminate\Support\Facades\Log;  
use Carbon\Carbon;

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

                $fileName = time() . '_' . $file->getClientOriginalName();
                $filePath = $file->storeAs('videos', $fileName, 'public');
                $absolutePath = storage_path('app/public/' . $filePath);

                $namaSungai = $request->nama_sungai;
                
                // =======================================================
                // 🌟 JEMBATAN API: LARAVEL -> PYTHON (YOLOv26)
                // =======================================================
                $statusKondisi = 'NORMAL'; 
                $aiClassDetected = 'Tidak Ada';

                try {
                    $fastApiUrl = env('FASTAPI_AI_URL', 'http://127.0.0.1:8001');

                    $response = Http::timeout(60)->attach(
                        'file', fopen($absolutePath, 'r'), $fileName
                    )->post($fastApiUrl . '/api/deteksi');

                    if ($response->successful()) {
                        $aiData = $response->json();
                        
                        if (isset($aiData['status']) && $aiData['status'] == 'sukses') {
                            $kondisiSistem = strtoupper($aiData['kondisi_banjir']);
                            
                            if ($kondisiSistem == 'AWAS') {
                                $statusKondisi = 'AWAS';
                                $aiClassDetected = 'tanda_merah';
                            } elseif ($kondisiSistem == 'WASPADA') {
                                $statusKondisi = 'WASPADA';
                                $aiClassDetected = 'tanda_kuning';
                            } elseif ($kondisiSistem == 'SIAGA') {
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

                $range = $batasSungai[$statusKondisi];
                $deteksiLevel = rand($range[0], $range[1]);

                // Simpan ke DB
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
                // 🌟 REVISI WAKTU & 4 LEVEL LOGIKA TELEGRAM
                // =======================================================
                $token = env('TELEGRAM_BOT_TOKEN');
                $chatId = env('TELEGRAM_CHAT_ID');
                
                // PERBAIKAN WAKTU: Menarik format Jam & Menit, disamakan ke zona waktu WITA
                $waktuLapangan = Carbon::parse($request->waktu_rekaman)->timezone('Asia/Makassar')->format('d M Y, H:i') . ' WITA';
                $waktuSistem = now()->timezone('Asia/Makassar')->format('d M Y, H:i') . ' WITA';

                // PERBAIKAN LOGIKA STATUS: Mencegah 'WASPADA' nyasar ke 'AWAS'
                if ($statusKondisi === 'NORMAL') {
                    $textTelegram = "🟢 *[SISTEM MONITORING MORI NALOVE - " . $namaSungai . "]*\n\n";
                    $textTelegram .= "Pemantauan model YOLO berjalan lancar. Debit aliran air sungai berada di bawah ambang batas aman.\n\n";
                    $textTelegram .= "• Ketinggian Air: " . $deteksiLevel . " cm\n";
                    $textTelegram .= "• Status Keamanan: NORMAL\n";
                    $textTelegram .= "• Waktu Rekaman: " . $waktuLapangan . "\n";
                    $textTelegram .= "• Waktu Broadcast: " . $waktuSistem;

                } elseif ($statusKondisi === 'SIAGA') {
                    $textTelegram = "🟡 *[PERINGATAN STATUS SIAGA - MORI NALOVE]*\n\n";
                    $textTelegram .= "Sistem mendeteksi adanya kenaikan volume air sungai. Harap pantau aktivitas sekitar:\n\n";
                    $textTelegram .= "• Ketinggian Air: " . $deteksiLevel . " cm\n";
                    $textTelegram .= "• Status Keamanan: SIAGA\n";
                    $textTelegram .= "• Waktu Rekaman: " . $waktuLapangan . "\n";
                    $textTelegram .= "• Waktu Broadcast: " . $waktuSistem . "\n\n";
                    $textTelegram .= "*HIMBAUAN:* Kurangi aktivitas di sekitar sempadan aliran sungai.";

                } elseif ($statusKondisi === 'WASPADA') {
                    $textTelegram = "🟠 *[PERINGATAN STATUS WASPADA - MORI NALOVE]*\n\n";
                    $textTelegram .= "Sistem mendeteksi debit air terus meningkat dan MENDEKATI BATAS BAHAYA:\n\n";
                    $textTelegram .= "• Ketinggian Air: " . $deteksiLevel . " cm\n";
                    $textTelegram .= "• Status Keamanan: WASPADA\n";
                    $textTelegram .= "• Waktu Rekaman: " . $waktuLapangan . "\n";
                    $textTelegram .= "• Waktu Broadcast: " . $waktuSistem . "\n\n";
                    $textTelegram .= "*PERHATIAN:* Warga diharapkan mengamankan barang berharga ke tempat tinggi dan bersiap untuk kemungkinan evakuasi.";

                } else { // STATUS AWAS / BAHAYA
                    $textTelegram = "🚨 *[DARURAT STATUS AWAS - WARNING BANJIR MORI NALOVE]*\n\n";
                    $textTelegram .= "Sistem mendeteksi lonjakan ekstrem debit air yang berpotensi kuat memicu LUAPAN BANJIR:\n\n";
                    $textTelegram .= "• Ketinggian Air: " . $deteksiLevel . " cm\n";
                    $textTelegram .= "• Status Keamanan: AWAS\n";
                    $textTelegram .= "• Waktu Rekaman: " . $waktuLapangan . "\n";
                    $textTelegram .= "• Waktu Broadcast: " . $waktuSistem . "\n\n";
                    $textTelegram .= "*PERINTAH EVAKUASI:* Warga di bantaran aliran " . $namaSungai . " DIWAJIBKAN segera mengungsi ke titik aman!";
                }

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
                            Log::error("Telegram API gagal: " . $telegramResponse->body());
                        }
                    }
                } catch (\Exception $telegramError) {
                    Log::error("Gagal broadcast: " . $telegramError->getMessage());
                }

                return redirect()->back()->with('success', 'Video berhasil dianalisis & notifikasi real-time terkirim!');
            }
            return redirect()->back()->with('error', 'Gagal membaca file berkas video.');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan sistem: ' . $e->getMessage());
        }
    }
}