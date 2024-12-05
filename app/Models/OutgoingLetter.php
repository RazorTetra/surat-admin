<?php
// app/Models/OutgoingLetter.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OutgoingLetter extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'reference_number',
        'recipient',
        'recipient_address',
        'subject',
        'content',
        'letter_date',
        'type',
        'priority',
        'status',
        'notes',
        'created_by',
        'approved_by',
        'sent_at'
    ];

    protected $casts = [
        'letter_date' => 'date',
        'sent_at' => 'datetime'
    ];

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function approvedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
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