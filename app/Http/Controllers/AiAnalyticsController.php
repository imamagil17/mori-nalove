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
        
        // Status Prediksi
        if ($predictedLevel <= 35) {
            $statusPrediksi = 'WASPADA';
        } elseif ($predictedLevel <= 70) {
            $statusPrediksi = 'SIAGA';
        } else {
            $statusPrediksi = 'AWAS';
        }
        
        // Rain Intensity Estimation
        $rainIntensity = 10; // default cerah
        try {
            $response = Http::get("https://api.open-meteo.com/v1/forecast", [
                'latitude' => -0.8917,
                'longitude' => 119.8707,
                'current' => 'weather_code',
                'timezone' => 'Asia/Makassar'
            ]);
            if ($response->successful()) {
                $code = $response->json()['current']['weather_code'];
                
                // Rain intensity logic based on WMO code
                if (in_array($code, [65, 67, 75, 81, 82, 85, 86, 95, 96, 99])) {
                    $rainIntensity = 90; // Heavy rain / Storm
                } elseif (in_array($code, [61, 63, 66, 71, 73, 80])) {
                    $rainIntensity = 60; // Rain
                } elseif (in_array($code, [51, 53, 55, 56, 57])) {
                    $rainIntensity = 40; // Drizzle
                }
            }
        } catch (\Exception $e) {
            // Fallback
            $rainIntensity = 50;
        }
        
        // Risk Score
        $riskScore = round(($currentLevel * 0.7) + ($rainIntensity * 0.3));
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
