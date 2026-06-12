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

        // SINKRONISASI 100% DENGAN VIDEO UPLOAD CONTROLLER
        $thresholds = [
            'Sungai Gumbasa'   => [ 'siaga' => 150, 'waspada' => 350, 'awas' => 450, 'max' => 520 ],
            'Sungai Lariang'   => [ 'siaga' => 250, 'waspada' => 450, 'awas' => 600, 'max' => 700 ],
            'Sungai Lindu'     => [ 'siaga' => 190, 'waspada' => 390, 'awas' => 500, 'max' => 580 ],
            'Sungai Samba'     => [ 'siaga' => 100, 'waspada' => 300, 'awas' => 400, 'max' => 480 ],
            'Sungai Pakuli'    => [ 'siaga' => 160, 'waspada' => 360, 'awas' => 480, 'max' => 560 ],
            'Sungai Marawola'  => [ 'siaga' => 120, 'waspada' => 320, 'awas' => 420, 'max' => 490 ],
            'Sungai Palolo'    => [ 'siaga' => 140, 'waspada' => 340, 'awas' => 460, 'max' => 530 ],
            'Sungai Kulawi'    => [ 'siaga' => 200, 'waspada' => 400, 'awas' => 520, 'max' => 610 ],
            'Sungai Ngatabaru' => [ 'siaga' => 130, 'waspada' => 330, 'awas' => 430, 'max' => 500 ],
            'Sungai Wuno'      => [ 'siaga' => 110, 'waspada' => 310, 'awas' => 410, 'max' => 480 ],
            'Sungai Bangga'    => [ 'siaga' => 170, 'waspada' => 370, 'awas' => 470, 'max' => 550 ],
        ];

        $rules = $thresholds[$namaSungai] ?? [ 'siaga' => 250, 'waspada' => 350, 'awas' => 450, 'max' => 550 ];

        $logs = \App\Models\VideoUploadLog::where('nama_sungai', $namaSungai)
                          ->latest()->take(20)->get()->reverse()->values();
        
        $currentLevel = 0; $predictedLevel = 0; $riskScore = 0;
        $statusPrediksi = 'NORMAL';
        $chartLabels = []; $chartData = [];
        
        if ($logs->count() > 0) {
            $currentLevel = $logs->last()->nilai_level;
            
            if ($logs->count() >= 2) {
                $n = $logs->count();
                $sumX = 0; $sumY = 0; $sumXY = 0; $sumXX = 0;
                $firstTime = $logs->first()->created_at->timestamp;
                
                foreach ($logs as $log) {
                    $x = ($log->created_at->timestamp - $firstTime) / 60;
                    $y = $log->nilai_level;
                    
                    $sumX += $x; $sumY += $y; $sumXY += ($x * $y); $sumXX += ($x * $x);
                    $chartLabels[] = $log->created_at->format('H:i'); 
                    $chartData[] = $y; 
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
                $predictedLevel = $currentLevel;
                $chartLabels[] = $logs->first()->created_at->format('H:i');
                $chartData[] = $logs->first()->nilai_level;
            }
        }
        
        $predictedLevel = max(0, min($rules['max'], round($predictedLevel, 1)));
        
        // PERBAIKAN LOGIKA 4 STATUS DAN SKOR RISIKO
        if ($predictedLevel < $rules['siaga']) {
            $statusPrediksi = 'NORMAL';
            $riskScore = round(($predictedLevel / $rules['siaga']) * 25); 
        } elseif ($predictedLevel < $rules['waspada']) {
            $statusPrediksi = 'SIAGA';
            $riskScore = round(26 + (($predictedLevel - $rules['siaga']) / ($rules['waspada'] - $rules['siaga'])) * 24);
        } elseif ($predictedLevel < $rules['awas']) {
            $statusPrediksi = 'WASPADA';
            $riskScore = round(51 + (($predictedLevel - $rules['waspada']) / ($rules['awas'] - $rules['waspada'])) * 24);
        } else {
            $statusPrediksi = 'AWAS';
            $riskScore = round(76 + (($predictedLevel - $rules['awas']) / ($rules['max'] - $rules['awas'])) * 24);
        }
        
        $riskScore = max(0, min(100, $riskScore));
        
        // Sesuaikan kembali key untuk dikirim ke JS Chart (agar warnanya cocok)
        $jsThresholds = [
            'waspada' => $rules['siaga'], // Garis Kuning Bawah (Siaga)
            'siaga'   => $rules['waspada'], // Garis Oranye (Waspada)
            'bahaya'  => $rules['awas'],    // Garis Merah (Awas)
            'max'     => $rules['max']
        ];

        return response()->json([
            'success' => true,
            'data' => [
                'sungai' => $namaSungai,
                'current_level' => $currentLevel,
                'predicted_level' => $predictedLevel,
                'prediction_status' => $statusPrediksi,
                'risk_score' => $riskScore,
                'chart_labels' => $chartLabels,
                'chart_data' => $chartData,
                'thresholds' => $jsThresholds 
            ]
        ]);
    }
}