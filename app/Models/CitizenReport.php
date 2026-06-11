<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CitizenReport extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_pelapor', 
        'kecamatan_desa',     
        'lokasi',             
        'koordinat_lokasi',   
        'tingkat_genangan',   
        'deskripsi', 
        'jumlah_terdampak',   
        'fasilitas_rusak',    
        'foto_bukti', 
        'status'
    ];

    protected $casts = [
        'foto_bukti' => 'array', // Beritahu Laravel ini adalah kumpulan banyak file (Array)
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
