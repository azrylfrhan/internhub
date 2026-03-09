<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomWorkingDay extends Model
{
    use HasFactory;

    protected $fillable = [
        'tanggal_mulai',
        'tanggal_selesai',
        'jam_masuk',
        'jam_pulang',
        'keterangan',
    ];

    protected $casts = [
        'tanggal_mulai' => 'date',
        'tanggal_selesai' => 'date',
    ];
}
