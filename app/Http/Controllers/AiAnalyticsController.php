<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LogDeteksi;
use Illuminate\Support\Facades\Http;

class AiAnalyticsController extends Controller
{
    public function index()
    {
        // Mengambil 20 data log deteksi terbaru, diurutkan kronologis untuk regresi linear
        $logs = LogDeteksi::latest()->take(20)->get()->reverse()->values();
        
        $currentLevel = 0;
        $predictedLevel = 0;
        $riskScore = 0;
        $statusPrediksi = 'NORMAL';
        
        if ($logs->count() > 0) {
            $currentLevel = $logs->last()->nilai_level;
            
            if ($logs->count() >= 2) {
                // 📊 Mulai Hitung Rumus Matematika Linear Regression
                $n = $logs->count();
                $sumX = 0;
                $sumY = 0;
                $sumXY = 0;
                $sumXX = 0;
                
                $firstTime = $logs->first()->created_at->timestamp;
                
                foreach ($logs as $log) {
                    // X = interval menit dihitung dari log pertama data masuk
                    $x = ($log->created_at->timestamp - $firstTime) / 60;
                    $y = $log->nilai_level;
                    
                    $sumX += $x;
                    $sumY += $y;
                    $sumXY += ($x * $y);
                    $sumXX += ($x * $x);
                }
                
                $denominator = ($n * $sumXX) - ($sumX * $sumX);
                if ($denominator != 0) {
                    $m = (($n * $sumXY) - ($sumX * $sumY)) / $denominator; // Kemiringan Tren (Slope)
                    $c = ($sumY - ($m * $sumX)) / $n; // Titik Potong (Intercept)
                    
                    $currentX = ($logs->last()->created_at->timestamp - $firstTime) / 60;
                    $predictedX = $currentX + 30; // Prediksi posisi air 30 menit ke depan
                    
                    $predictedLevel = ($m * $predictedX) + $c;
                } else {
                    $predictedLevel = $currentLevel;
                }
            } else {
                $predictedLevel = $currentLevel;
            }
        }
        
        // Membatasi hasil prediksi agar tetap berada di skala rasional 0% - 100%
        $predictedLevel = max(0, min(100, round($predictedLevel, 1)));
        
        if ($predictedLevel < 50) {
            $statusPrediksi = 'NORMAL';
            // Menghasilkan nilai proporsional bertahap di rentang 0 - 35
            $riskScore = round(($predictedLevel / 50) * 35); 
        } elseif ($predictedLevel < 80) {
            $statusPrediksi = 'SIAGA';
            // Menghasilkan nilai proporsional bertahap di rentang 36 - 79
            $riskScore = round(36 + (($predictedLevel - 50) / 30) * 43);
        } else {
            $statusPrediksi = 'BAHAYA';
            // Menghasilkan nilai proporsional bertahap di rentang 80 - 100
            $riskScore = round(80 + (($predictedLevel - 80) / 20) * 20);
        }
        
        // Mengunci batas akhir skor risiko agar tidak melenceng dari standar 0-100
        $riskScore = max(0, min(100, $riskScore));
        
        return response()->json([
            'success' => true,
            'data' => [
                'current_level' => $currentLevel,
                'predicted_level' => $predictedLevel,
                'prediction_status' => $statusPrediksi,
                'risk_score' => $riskScore
            ]
        ]);
    }
}