<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('citizen_reports', function (Blueprint $table) {
            $table->string('kecamatan_desa')->after('nama_pelapor')->nullable();
            $table->string('koordinat_lokasi')->after('lokasi')->nullable();
            $table->integer('jumlah_terdampak')->after('deskripsi')->nullable();
            $table->text('fasilitas_rusak')->after('jumlah_terdampak')->nullable();
        });
    }

    public function down()
    {
        Schema::table('citizen_reports', function (Blueprint $table) {
            $table->dropColumn(['kecamatan_desa', 'koordinat_lokasi', 'jumlah_terdampak', 'fasilitas_rusak']);
        });
    }
};