<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Presensi extends Model
{
    protected $fillable = ['user_id', 'tanggal', 'jam_masuk', 'jam_pulang', 'lokasi_masuk', 'lokasi_pulang', 'foto_masuk', 'foto_pulang', 'status', 'keterangan'];

    protected $casts = [
        'tanggal' => 'date',
    ];

    /**
     * Format jam_masuk tanpa detik (HH:mm saja)
     */
    protected function jamMasuk(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $value ? substr($value, 0, 5) : null,
        );
    }

    /**
     * Format jam_pulang tanpa detik (HH:mm saja)
     */
    protected function jamPulang(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $value ? substr($value, 0, 5) : null,
        );
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
