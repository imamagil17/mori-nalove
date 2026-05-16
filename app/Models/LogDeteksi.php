<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LogDeteksi extends Model
{
    use HasFactory;

    protected $fillable = ['status', 'nilai_level'];
}
