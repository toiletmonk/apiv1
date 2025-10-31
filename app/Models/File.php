<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class File extends Model
{
    protected $fillable = [
        'user_id',
        'original_name',
        'filename',
        'filetype',
        'filepath',
        'type',
        'status',
        'metadata',
    ];

    public function user()
    {
        $this->belongsTo(User::class);
    }

    public function isReady(): bool
    {
        return $this->status === 'ready';
    }

    public function isProcessing(): bool
    {
        return $this->status === 'ready';
    }

    public function isFailed(): bool
    {
        return $this->status === 'failed';
    }

    public function getUrlAttribute(): ?string
    {
        return $this->filepath
            ? Storage::disk('s3')->url($this->filepath)
            : null;
    }
}
