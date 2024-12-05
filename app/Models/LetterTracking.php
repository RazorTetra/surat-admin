<?php
// app/Models/LetterTracking.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LetterTracking extends Model
{
    use HasFactory;

    protected $fillable = [
        'status',
        'description',
        'metadata',
        'user_id'
    ];

    protected $casts = [
        'metadata' => 'array'
    ];

    public function trackable(): MorphTo
    {
        return $this->morphTo();
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}