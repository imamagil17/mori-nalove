<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CitizenReport extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'nama_pelapor',
        'lokasi',
        'tingkat_genangan',
        'deskripsi',
        'status',
        'foto_bukti',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
