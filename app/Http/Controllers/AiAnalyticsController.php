<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
// PERBAIKAN 1: Panggil model VideoUploadLog, bukan LogDeteksi
use App\Models\VideoUploadLog; 

class AiAnalyticsController extends Controller
{
    public function index(Request $request)
    {
        $namaSungai = $request->input('sungai', 'Sungai Gumbasa');

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

        $rules = $thresholds[$namaSungai] ?? [ 'waspada' => 200, 'siaga' => 300, 'bahaya' => 400, 'max' => 450 ];

        // PERBAIKAN 2: Menggunakan tabel VideoUploadLog
        $logs = VideoUploadLog::where('nama_sungai', $namaSungai)
                          ->latest()
                          ->take(20)
                          ->get()
                          ->reverse()
                          ->values();
        
        $currentLevel = 0;
        $predictedLevel = 0;
        $riskScore = 0;
        $statusPrediksi = 'NORMAL';
        
        // PERBAIKAN 3: Siapkan keranjang kosong untuk data Grafik (Waktu dan Ketinggian)
        $chartLabels = [];
        $chartData = [];
        
        if ($logs->count() > 0) {
            $currentLevel = $logs->last()->nilai_level;
            
            if ($logs->count() >= 2) {
                // Kalkulasi Prediksi Linear Regression
                $n = $logs->count();
                $sumX = 0;
                $sumY = 0;
                $sumXY = 0;
                $sumXX = 0;
                
                $firstTime = $logs->first()->created_at->timestamp;
                
                foreach ($logs as $log) {
                    $x = ($log->created_at->timestamp - $firstTime) / 60;
                    $y = $log->nilai_level;
                    
                    $sumX += $x;
                    $sumY += $y;
                    $sumXY += ($x * $y);
                    $sumXX += ($x * $x);

                    // Isi keranjang grafik untuk dikirim ke View/Blade
                    $chartLabels[] = $log->created_at->format('H:i'); // Ambil Jam:Menit
                    $chartData[] = $y; // Ambil nilai sentimeter
                }
                
                $denominator = ($n * $sumXX) - ($sumX * $sumX);
                if ($denominator != 0) {
                    $m = (($n * $sumXY) - ($sumX * $sumY)) / $denominator; 
                    $c = ($sumY - ($m * $sumX)) / $n;                     
                    
                    $currentX = ($logs->last()->created_at->timestamp - $firstTime) / 60;
                    $predictedX = $currentX + 30; 
                    
                    $predictedLevel = ($m * $predictedX) + $c;
                } else {
                    $predictedLevel = $currentLevel;
                }
            } else {
                // Jika data baru ada 1, isi keranjang grafik langsung
                $predictedLevel = $currentLevel;
                $chartLabels[] = $logs->first()->created_at->format('H:i');
                $chartData[] = $logs->first()->nilai_level;
            }
        }
        
        $predictedLevel = max(0, min($rules['max'], round($predictedLevel, 1)));
        
        if ($predictedLevel < $rules['waspada']) {
            $statusPrediksi = 'NORMAL';
            $riskScore = round(($predictedLevel / $rules['waspada']) * 35); 
        } elseif ($predictedLevel < $rules['bahaya']) {
            $statusPrediksi = 'SIAGA';
            $riskScore = round(36 + (($predictedLevel - $rules['waspada']) / ($rules['bahaya'] - $rules['waspada'])) * 43);
        } else {
            $statusPrediksi = 'BAHAYA';
            $riskScore = round(80 + (($predictedLevel - $rules['bahaya']) / ($rules['max'] - $rules['bahaya'])) * 20);
        }
        
        $riskScore = max(0, min(100, $riskScore));
        
        return response()->json([
            'success' => true,
            'data' => [
                'sungai' => $namaSungai,
                'current_level' => $currentLevel,
                'predicted_level' => $predictedLevel,
                'prediction_status' => $statusPrediksi,
                'risk_score' => $riskScore,
                // PERBAIKAN 4: Kirim keranjang grafik ke JavaScript
                'chart_labels' => $chartLabels,
                'chart_data' => $chartData,
                'thresholds' => $rules // Kirim ambang batas agar garis horizontal grafik bisa menyesuaikan sungai
            ]
        ]);
    }
}