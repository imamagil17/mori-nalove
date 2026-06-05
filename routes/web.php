<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\LogController;
use App\Http\Controllers\WeatherController;
use App\Http\Controllers\AiAnalyticsController;
use App\Http\Controllers\ChatbotController;
use App\Http\Controllers\NotificationLogController;
use App\Http\Controllers\BeritaController;
use App\Http\Controllers\CitizenReportController;
use App\Http\Controllers\VideoUploadController;

// 1. Halaman Beranda / Dashboard Publik (Tanpa Autentikasi)
Route::get('/', [App\Http\Controllers\UserDashboardController::class, 'index'])->name('user.dashboard');

// 2. Kelompok Rute Khusus Admin (Proteksi Middleware Auth, Verified, & Role Admin)
Route::middleware(['auth', 'verified', 'role:admin'])->group(function () {
    
    Route::get('/admin/dashboard', function () {
        // Tarik data berita untuk preview di admin
        $beritas = \App\Models\Berita::latest()->get(); 
        $reports = \App\Models\CitizenReport::with('user')->latest()->take(3)->get();
        
        // Ringkasan data untuk card atas (Audit Controller backend check)
        $totalReports = \App\Models\CitizenReport::count();
        $totalBerita = \App\Models\Berita::count();
        $totalVideos = \App\Models\VideoUploadLog::count();
        $totalAlerts = \App\Models\VideoUploadLog::whereIn('status_kondisi', ['Siaga', 'Bahaya', 'SIAGA', 'BAHAYA', 'Waspada', 'WASPADA', 'Awas', 'AWAS'])->count();

        return view('admin.dashboard', compact('beritas', 'reports', 'totalReports', 'totalBerita', 'totalVideos', 'totalAlerts'));
    })->name('admin.dashboard');

    Route::get('/admin/notifications', [NotificationLogController::class, 'index'])->name('admin.notifications.index');
    Route::post('/admin/notifications/test', [NotificationLogController::class, 'testSend'])->name('admin.notifications.test');



    // CRUD Berita Admin
    Route::get('/admin/berita', [BeritaController::class, 'index'])->name('admin.berita.index');
    Route::post('/admin/berita', [BeritaController::class, 'store'])->name('admin.berita.store');
    Route::get('/admin/berita/{id}/edit', [BeritaController::class, 'edit'])->name('admin.berita.edit');
    Route::put('/admin/berita/{id}', [BeritaController::class, 'update'])->name('admin.berita.update');
    Route::delete('/admin/berita/{id}', [BeritaController::class, 'destroy'])->name('admin.berita.destroy');

    // CRUD Laporan Warga di Sisi Admin
    Route::post('/admin/reports/{id}/verify', [CitizenReportController::class, 'verify'])->name('admin.reports.verify');
    Route::delete('/admin/reports/{id}', [CitizenReportController::class, 'destroy'])->name('admin.reports.destroy');
    Route::get('/admin/citizen-reports/{id}', function ($id) {
        $report = \App\Models\CitizenReport::with('user')->findOrFail($id);
        return view('admin.citizen_reports.show', compact('report'));
    })->name('admin.citizen_reports.show');
    Route::get('/admin/citizen-reports', [CitizenReportController::class, 'index'])->name('admin.citizen_reports.index');

    Route::get('/admin/videos/kelola-video', [VideoUploadController::class, 'index'])->name('admin.kelola_video.index');
    Route::post('/admin/videos/kelola-video', [VideoUploadController::class, 'store'])->name('admin.kelola_video.store');
});

// 3. Smart Route Redirect Fallback (Rujukan Arah Rute)
Route::get('/dashboard', function () {
    if (auth()->check() && auth()->user()->role === 'admin') {
        return redirect()->route('admin.dashboard');
    }
    return redirect()->route('user.dashboard');
})->name('dashboard');

// 4. Kelompok Rute Manajemen Profil Pengguna (Wajib Login)
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// 5. Kelompok Rute Jalur API & Sistem Integrasi Perangkat Cerdas
Route::get('/api/logs', [LogController::class, 'index'])->name('logs.index');
Route::post('/api/logs', [LogController::class, 'store'])->name('logs.store');

Route::get('/api/notifications', [LogController::class, 'notifications'])->name('notifications.index');

Route::get('/api/weather', [WeatherController::class, 'index'])->name('weather.index');
Route::get('/api/analytics', [AiAnalyticsController::class, 'index'])->name('analytics.index');

// RUTE CHATBOT REVISI (Mendukung GET/POST Publik untuk Koneksi Groq Llama 3.3)
Route::match(['get', 'post'], '/api/chat', [ChatbotController::class, 'ask'])->name('chat.ask');

Route::post('/api/reports', [CitizenReportController::class, 'store'])->name('reports.store');

// 6. Jalur Otentikasi Bawaan Laravel Breeze
require __DIR__.'/auth.php';