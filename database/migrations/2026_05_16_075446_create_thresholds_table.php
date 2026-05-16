<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('thresholds', function (Blueprint $table) {
            $table->id();
            $table->string('status_name'); // Nama status (misal: Aman, Siaga, Awas)
            $table->float('min_percentage'); // Batas minimal (misal: 0, 40, 75)
            $table->string('color_hex')->nullable(); // Warna UI (misal: #ef4444)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('thresholds');
    }
};
