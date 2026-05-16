<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\LogController;
use App\Http\Controllers\WeatherController;
use App\Http\Controllers\AiAnalyticsController;
use App\Http\Controllers\ChatbotController;
use App\Http\Controllers\Admin\NotificationLogController;
use App\Http\Controllers\Admin\WaterLevelLogController;
use App\Http\Controllers\BeritaController;


Route::get('/', function () {
    return view('welcome');
});

// Admin routes
Route::middleware(['auth', 'verified', 'role:admin'])->group(function () {
    
    // 👇 INI YANG KITA UBAH AGAR ADMIN BISA LIHAT BERITA 👇
    Route::get('/admin/dashboard', function () {
        // Tarik data berita untuk preview di admin
        $beritas = \App\Models\Berita::latest()->get(); 
        return view('admin.dashboard', compact('beritas'));
    })->name('admin.dashboard');

    Route::get('/admin/notifications', [NotificationLogController::class, 'index'])->name('admin.notifications.index');
    Route::post('/admin/notifications/test', [NotificationLogController::class, 'testSend'])->name('admin.notifications.test');

    Route::get('/admin/water-logs', [WaterLevelLogController::class, 'index'])->name('admin.water_logs.index');
    Route::get('/admin/water-logs/create', [WaterLevelLogController::class, 'create'])->name('admin.water_logs.create');
    Route::post('/admin/water-logs', [WaterLevelLogController::class, 'store'])->name('admin.water_logs.store');

    // Tambahan Rute CRUD Berita Admin
    Route::get('/admin/berita', [BeritaController::class, 'index'])->name('admin.berita.index');
    Route::post('/admin/berita', [BeritaController::class, 'store'])->name('admin.berita.store');
    Route::get('/admin/berita/{id}/edit', [BeritaController::class, 'edit'])->name('admin.berita.edit');
    Route::put('/admin/berita/{id}', [BeritaController::class, 'update'])->name('admin.berita.update');
    Route::delete('/admin/berita/{id}', [BeritaController::class, 'destroy'])->name('admin.berita.destroy');
});

// Route Dashboard User
Route::get('/dashboard', function () {
    // Ambil semua data berita dari database urut dari yang paling baru
    $beritas = \App\Models\Berita::latest()->get();
    
    // Kita arahkan ke folder user, file dashboard, sambil membawa variabel $beritas
    return view('user.dashboard', compact('beritas')); 
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Route for API
Route::get('/api/logs', [LogController::class, 'index'])->name('logs.index');
Route::post('/api/logs', [LogController::class, 'store'])->name('logs.store');

Route::get('/api/notifications', [LogController::class, 'notifications'])->name('notifications.index');

Route::get('/api/weather', [WeatherController::class, 'index'])->name('weather.index');
Route::get('/api/analytics', [AiAnalyticsController::class, 'index'])->name('analytics.index');
Route::post('/api/chat', [ChatbotController::class, 'ask'])->name('chat.ask');

require __DIR__.'/auth.php';