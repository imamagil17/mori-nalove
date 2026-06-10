<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('log_deteksis', function (Blueprint $table) {
            $table->id();
            $table->string('nama_sungai')->default('Sungai Gumbasa'); // [PERBAIKAN]
            $table->string('status');
            $table->decimal('nilai_level', 8, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('log_deteksis');
    }
};
