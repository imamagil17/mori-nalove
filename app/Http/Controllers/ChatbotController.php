<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Http;

class ChatbotController extends Controller
{
    public function ask(Request $request)
    {
        $validated = $request->validate([
            'prompt' => 'required|string',
            'context' => 'required|string'
        ]);

        $userPrompt = $validated['prompt'];
        $context = $validated['context'];

        $apiKey = env('GEMINI_API_KEY');

        $systemPrompt = "Anda adalah asisten mitigasi Flood Vision. Jawablah pertanyaan pengguna secara singkat dan solutif. Berikut adalah konteks saat ini: " . $context;
        $fullPrompt = $systemPrompt . "\n\nPertanyaan: " . $userPrompt;

        if (empty($apiKey)) {
            // Dummy logic based on context
            $reply = "Berdasarkan sistem (Dummy AI): Saat ini tingkat air cukup krusial. Saya menyarankan Anda untuk terus memantau peringatan dari BPBD setempat dan menyiapkan tas siaga bencana jika terjadi peningkatan debit air secara tiba-tiba.";
            if (stripos($context, 'AMAN') !== false) {
                $reply = "Berdasarkan sistem (Dummy AI): Kondisi saat ini aman. Anda bisa beraktivitas seperti biasa, namun tetap waspada jika terjadi hujan deras.";
            }

            return response()->json([
                'success' => true,
                'reply' => $reply
            ]);
        }

        try {
            // Gemini API Request
            $response = Http::post("https://generativelanguage.googleapis.com/v1beta/models/gemini-pro:generateContent?key=" . $apiKey, [
                'contents' => [
                    [
                        'parts' => [
                            ['text' => $fullPrompt]
                        ]
                    ]
                ]
            ]);

            if ($response->successful()) {
                $data = $response->json();
                $replyText = $data['candidates'][0]['content']['parts'][0]['text'] ?? "Maaf, saya tidak bisa memproses jawaban saat ini.";
                
                return response()->json([
                    'success' => true,
                    'reply' => $replyText
                ]);
            }

            throw new \Exception('Failed to fetch from Gemini');

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'reply' => 'Maaf, terjadi kesalahan pada sistem AI kami saat memproses permintaan Anda.'
            ], 500);
        }
    }
}
