<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Threshold extends Model
{
    use HasFactory;

    protected $fillable = ['status_name', 'min_percentage', 'color_hex'];

    // Relasi: 1 Threshold punya banyak Log Riwayat
    public function waterLevelLogs()
    {
        return $this->hasMany(WaterLevelLog::class);
    }
}