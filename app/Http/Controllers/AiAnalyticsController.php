<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\LogDeteksi;
use Illuminate\Support\Facades\Http;

class AiAnalyticsController extends Controller
{
    public function index()
    {
        $logs = LogDeteksi::latest()->take(20)->get()->reverse()->values();
        
        $currentLevel = 0;
        $predictedLevel = 0;
        $riskScore = 0;
        $statusPrediksi = 'AMAN';
        
        if ($logs->count() > 0) {
            $currentLevel = $logs->last()->nilai_level;
            
            if ($logs->count() >= 2) {
                // Linear Regression
                $n = $logs->count();
                $sumX = 0;
                $sumY = 0;
                $sumXY = 0;
                $sumXX = 0;
                
                $firstTime = $logs->first()->created_at->timestamp;
                
                foreach ($logs as $log) {
                    // X = minutes since first log
                    $x = ($log->created_at->timestamp - $firstTime) / 60;
                    $y = $log->nilai_level;
                    
                    $sumX += $x;
                    $sumY += $y;
                    $sumXY += ($x * $y);
                    $sumXX += ($x * $x);
                }
                
                $denominator = ($n * $sumXX) - ($sumX * $sumX);
                if ($denominator != 0) {
                    $m = (($n * $sumXY) - ($sumX * $sumY)) / $denominator; // Slope
                    $c = ($sumY - ($m * $sumX)) / $n; // Intercept
                    
                    $currentX = ($logs->last()->created_at->timestamp - $firstTime) / 60;
                    $predictedX = $currentX + 30; // 30 minutes ahead
                    
                    $predictedLevel = ($m * $predictedX) + $c;
                } else {
                    $predictedLevel = $currentLevel;
                }
            } else {
                $predictedLevel = $currentLevel;
            }
        }
        
        // Clamp predicted level
        $predictedLevel = max(0, min(100, round($predictedLevel, 1)));
        
        // Status Prediksi & Risk Score berdasarkan Current Level
        if ($currentLevel < 50) {
            $statusPrediksi = 'AMAN';
            $riskScore = rand(10, 35);
        } elseif ($currentLevel < 70) {
            $statusPrediksi = 'WASPADA';
            $riskScore = rand(36, 70);
        } elseif ($currentLevel < 85) {
            $statusPrediksi = 'SIAGA';
            $riskScore = rand(71, 85);
        } else {
            $statusPrediksi = 'AWAS';
            $riskScore = rand(86, 100);
        }
        
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
