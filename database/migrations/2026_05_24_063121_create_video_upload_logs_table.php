<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('video_upload_logs', function (Blueprint $table) {
            $table->id();
            $table->string('nama_sungai'); // Menyimpan opsi: Gumbasa, Palu, Lindu, dll.
            $table->string('file_video');  // Menyimpan nama file asli / path video di storage
            $table->string('ukuran_file'); // Menyimpan informasi size (misal: 45 MB)
            $table->dateTime('waktu_rekaman'); // Tanggal & waktu rekaman diambil di lapangan
            $table->float('nilai_level')->default(0); // Hasil deteksi tinggi air dari YOLO (%)
            $table->string('status_kondisi')->default('Normal'); // Hasil klasifikasi: Normal, Siaga, Bahaya
            $table->text('keterangan')->nullable(); // Catatan tambahan dari admin
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('video_upload_logs');
    }
};
