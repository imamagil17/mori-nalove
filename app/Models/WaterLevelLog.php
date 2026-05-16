<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WaterLevelLog extends Model
{
    use HasFactory;

    protected $fillable = ['water_level', 'threshold_id', 'image_path', 'recorded_at'];

    // Relasi: 1 Log Riwayat dimiliki oleh 1 Threshold
    public function threshold()
    {
        return $this->belongsTo(Threshold::class);
    }
}