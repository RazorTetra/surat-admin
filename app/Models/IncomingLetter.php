<?php
// app/Models/IncomingLetter.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class IncomingLetter extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'reference_number',
        'origin',
        'sender',
        'recipient',
        'subject',
        'description',
        'letter_date',
        'received_date',
        'type',
        'priority',
        'status',
        'notes',
        'received_by',
        'processed_by'
    ];

    protected $casts = [
        'letter_date' => 'date',
        'received_date' => 'date'
    ];

    public function receivedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'received_by');
    }

    public function processedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'processed_by');
    }

    public function attachments(): MorphMany
    {
        return $this->morphMany(LetterAttachment::class, 'letterable');
    }

    public function tracking(): MorphMany
    {
        return $this->morphMany(LetterTracking::class, 'trackable');
    }
}