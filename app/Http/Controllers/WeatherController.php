<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Http;

class WeatherController extends Controller
{
    public function index()
    {
        try {
            // Using modern Open-Meteo current endpoint to get temperature, humidity, and weather code
            $response = Http::get("https://api.open-meteo.com/v1/forecast", [
                'latitude' => -0.8917,
                'longitude' => 119.8707,
                'current' => 'temperature_2m,relative_humidity_2m,weather_code',
                'timezone' => 'Asia/Makassar'
            ]);

            if ($response->successful()) {
                $data = $response->json();
                $current = $data['current'];
                
                $weatherCode = $current['weather_code'];
                $description = $this->getWmoDescription($weatherCode);
                
                return response()->json([
                    'success' => true,
                    'data' => [
                        'temp' => round($current['temperature_2m']),
                        'humidity' => $current['relative_humidity_2m'],
                        'description' => $description,
                        'icon' => 'default', // Lucide icons don't use this directly, handled in frontend if needed
                        'city' => 'Lokasi Pantau'
                    ]
                ]);
            }

            throw new \Exception('Failed to fetch from Open-Meteo');

        } catch (\Exception $e) {
            // Fallback
            return response()->json([
                'success' => false,
                'data' => [
                    'temp' => 32,
                    'humidity' => 65,
                    'description' => 'Cerah Berawan',
                    'icon' => 'default',
                    'city' => 'Lokasi (Dummy)'
                ]
            ]);
        }
    }

    private function getWmoDescription($code)
    {
        $map = [
            0 => 'Cerah',
            1 => 'Cerah Berawan',
            2 => 'Berawan',
            3 => 'Mendung',
            45 => 'Berkabut',
            48 => 'Kabut Tebal',
            51 => 'Gerimis Ringan',
            53 => 'Gerimis Sedang',
            55 => 'Gerimis Lebat',
            56 => 'Gerimis Beku Ringan',
            57 => 'Gerimis Beku Lebat',
            61 => 'Hujan Ringan',
            63 => 'Hujan Sedang',
            65 => 'Hujan Lebat',
            66 => 'Hujan Beku Ringan',
            67 => 'Hujan Beku Lebat',
            71 => 'Hujan Salju Ringan',
            73 => 'Hujan Salju Sedang',
            75 => 'Hujan Salju Lebat',
            77 => 'Hujan Salju',
            80 => 'Hujan Deras',
            81 => 'Hujan Sangat Deras',
            82 => 'Hujan Badai',
            85 => 'Hujan Salju Deras',
            86 => 'Hujan Salju Badai',
            95 => 'Badai Petir',
            96 => 'Badai Petir Ringan',
            99 => 'Badai Petir Lebat',
        ];

        return $map[$code] ?? 'Tidak Diketahui';
    }
}
