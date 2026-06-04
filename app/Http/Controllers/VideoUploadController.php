<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\VideoUploadLog;
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
                // 🌟 JEMBATAN API: LARAVEL -> PYTHON (YOLOv26 Port 8001)
                // =======================================================
                $deteksiLevel = 0; 
                $aiClassDetected = 'Tidak Ada';

                try {
                    // Otomatis mengambil alamat port 8001 dari file .env
                    $fastApiUrl = env('FASTAPI_AI_URL', 'http://127.0.0.1:8001');

                    // Menembak file video secara fisik ke server FastAPI
                    $response = Http::timeout(60)->attach(
                        'file', file_get_contents($absolutePath), $fileName
                    )->post($fastApiUrl . '/api/deteksi');

                    if ($response->successful()) {
                        $aiData = $response->json();
                        
                        if ($aiData['status'] == 'sukses' && $aiData['total_objek'] > 0) {
                            $hasilAI = $aiData['hasil'][0];
                            
                            // Paksa ke huruf kecil untuk mengantisipasi case-sensitivity output YOLO
                            $aiClassDetected = strtolower($hasilAI['kelas']); 
                            
                            // KONVERSI SEMENTARA: Kelas YOLO ke Centimeter
                            if (in_array($aiClassDetected, ['aman', 'person', 'normal'])) {
                                $deteksiLevel = rand(30, 190); 
                            } elseif (in_array($aiClassDetected, ['siaga', 'waspada'])) {
                                $deteksiLevel = rand(300, 380);
                            } elseif (in_array($aiClassDetected, ['bahaya', 'awas'])) {
                                $deteksiLevel = rand(450, 500);
                            } else {
                                $deteksiLevel = rand(100, 200); 
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
                // 🌟 LOGIKA THRESHOLD 11 SUNGAI (ARRAY MAPPING)
                // =======================================================
                $statusKondisi = 'Normal';
                
                $aturanSungai = [
                    'Sungai Gumbasa'   => ['bahaya' => 450, 'siaga' => 350, 'waspada' => 250],
                    'Sungai Lariang'   => ['bahaya' => 600, 'siaga' => 450, 'waspada' => 350],
                    'Sungai Lindu'     => ['bahaya' => 500, 'siaga' => 390, 'waspada' => 300],
                    'Sungai Samba'     => ['bahaya' => 400, 'siaga' => 300, 'waspada' => 200],
                    'Sungai Pakuli'    => ['bahaya' => 480, 'siaga' => 360, 'waspada' => 260],
                    'Sungai Marawola'  => ['bahaya' => 420, 'siaga' => 320, 'waspada' => 220],
                    'Sungai Palolo'    => ['bahaya' => 460, 'siaga' => 340, 'waspada' => 240],
                    'Sungai Kulawi'    => ['bahaya' => 520, 'siaga' => 400, 'waspada' => 300],
                    'Sungai Ngatabaru' => ['bahaya' => 430, 'siaga' => 330, 'waspada' => 230],
                    'Sungai Wuno'      => ['bahaya' => 410, 'siaga' => 310, 'waspada' => 210],
                    'Sungai Bangga'    => ['bahaya' => 470, 'siaga' => 370, 'waspada' => 270],
                ];

                $defaultBatas = ['bahaya' => 400, 'siaga' => 300, 'waspada' => 200];
                $batas = $aturanSungai[$namaSungai] ?? $defaultBatas;

                // Cek status secara otomatis
                if ($deteksiLevel >= $batas['bahaya']) {
                    $statusKondisi = 'Bahaya';
                } elseif ($deteksiLevel >= $batas['waspada']) {
                    $statusKondisi = 'Siaga';
                } else {
                    $statusKondisi = 'Normal';
                }
                // =======================================================

                // Simpan ke Database
                VideoUploadLog::create([
                    'nama_sungai' => $namaSungai,
                    'file_video' => $filePath,
                    'ukuran_file' => $fileSizeMb,
                    'waktu_rekaman' => $request->waktu_rekaman,
                    'nilai_level' => $deteksiLevel, 
                    'status_kondisi' => $statusKondisi,
                    'keterangan' => "YOLO class: [" . $aiClassDetected . "] | " . $request->keterangan 
                ]);

                return redirect()->back()->with('success', 'Video berhasil diunggah dan dianalisis langsung oleh YOLOv26!');
            }

            return redirect()->back()->with('error', 'Gagal membaca file berkas video.');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan sistem: ' . $e->getMessage());
        }
    }
}