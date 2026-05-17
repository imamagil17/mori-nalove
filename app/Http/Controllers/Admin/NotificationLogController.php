<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\NotificationLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class NotificationLogController extends Controller
{
    // Menampilkan halaman Log
    public function index()
    {
        // Ambil data dari yang paling baru
        $logs = NotificationLog::latest()->get(); 
        return view('admin.notifications.index', compact('logs'));
    }

    public function testSend()
    {
        $token = env('TELEGRAM_BOT_TOKEN');
        $chatId = env('TELEGRAM_CHAT_ID');
        
        $message = "🤖 [SISTEM TEST] \nPengujian Sistem Mitigasi Banjir Cerdas berjalan dengan normal. Bot mitigasi telah aktif dan siap memantau ketinggian air 24/7.";

        try {
            // ->withoutVerifying() digunakan untuk menembus blokir SSL di localhost XAMPP
            $response = Http::withoutVerifying()->post("https://api.telegram.org/bot{$token}/sendMessage", [
                'chat_id' => $chatId,
                'text' => $message,
            ]);

            if ($response->successful()) {
                NotificationLog::create([
                    'message' => $message,
                    'status' => 'Terkirim ✅',
                ]);
                return redirect()->back()->with('success', 'Cek HP sekarang! Pesan berhasil ditembak ke Telegram.');
            } else {
                // Mengambil alasan persis kenapa Telegram menolak pesanmu
                $errorReason = $response->json('description') ?? 'Gagal merespons API';
                return redirect()->back()->with('error', 'Gagal dari Telegram: ' . $errorReason);
            }

        } catch (\Exception $e) {
            // Menangkap jika ada kerusakan koneksi/sistem di laptop kamu
            return redirect()->back()->with('error', 'Error Sistem Laptop: ' . $e->getMessage());
        }
    }
}