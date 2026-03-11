<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class Permission extends Model
{
    protected $fillable = [
        'user_id',
        'start_date',
        'end_date',
        'permission_type',
        'reason',
        'attachment_path',
        'status',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    public function getPermissionTypeLabelAttribute(): string
    {
        return $this->permission_type === 'sakit' ? 'Sakit' : 'Alasan Lain';
    }

    public function getAttachmentUrlAttribute(): ?string
    {
        return $this->attachment_path ? Storage::url($this->attachment_path) : null;
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
