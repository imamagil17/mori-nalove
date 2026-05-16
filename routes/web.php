<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\LogController;
use App\Http\Controllers\WeatherController;
use App\Http\Controllers\AiAnalyticsController;
use App\Http\Controllers\ChatbotController;
use App\Http\Controllers\Admin\NotificationLogController;
use App\Http\Controllers\Admin\WaterLevelLogController;

Route::get('/', function () {
    return view('welcome');
});

// Admin routes
Route::middleware(['auth', 'verified', 'role:admin'])->group(function () {
    Route::get('/admin/dashboard', function () {
        return view('admin.dashboard');
    })->name('admin.dashboard');

    Route::get('/admin/notifications', [NotificationLogController::class, 'index'])->name('admin.notifications.index');
    Route::post('/admin/notifications/test', [NotificationLogController::class, 'testSend'])->name('admin.notifications.test');

    Route::get('/admin/water-logs', [WaterLevelLogController::class, 'index'])->name('admin.water_logs.index');
    Route::get('/admin/water-logs/create', [WaterLevelLogController::class, 'create'])->name('admin.water_logs.create');
    Route::post('/admin/water-logs', [WaterLevelLogController::class, 'store'])->name('admin.water_logs.store');
});

Route::get('/dashboard', function () {
    // Kita arahkan ke folder user, file dashboard
    return view('user.dashboard'); 
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Route for API
Route::get('/api/logs', [LogController::class, 'index'])->name('logs.index');
Route::post('/api/logs', [LogController::class, 'store'])->name('logs.store');
Route::get('/api/weather', [WeatherController::class, 'index'])->name('weather.index');
Route::get('/api/analytics', [AiAnalyticsController::class, 'index'])->name('analytics.index');
Route::post('/api/chat', [ChatbotController::class, 'ask'])->name('chat.ask');

require __DIR__.'/auth.php';
