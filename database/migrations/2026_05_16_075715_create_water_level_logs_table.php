<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('water_level_logs', function (Blueprint $table) {
            $table->id();
            $table->float('water_level'); // Tinggi air (contoh: 45.5)
            
            // SUDAH DIUBAH JADI NULLABLE (Boleh kosong untuk input manual)
            $table->foreignId('threshold_id')->nullable()->constrained('thresholds')->onDelete('cascade');
            
            $table->text('image_path')->nullable(); // Lokasi simpan foto dari OpenCV
            $table->timestamp('recorded_at')->useCurrent(); // Waktu terekam otomatis
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('water_level_logs');
    }
};