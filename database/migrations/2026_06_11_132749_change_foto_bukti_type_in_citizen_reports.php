<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('citizen_reports', function (Blueprint $table) {
            // Ubah menjadi longText agar muat menampung banyak nama file foto (Array JSON)
            $table->longText('foto_bukti')->change();
        });
    }

    public function down()
    {
        Schema::table('citizen_reports', function (Blueprint $table) {
            $table->string('foto_bukti')->change();
        });
    }
};