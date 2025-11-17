<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CertificationBadge extends Model
{
    protected $fillable = [
        'user_id',
        'certification_id',
        'type',
        'name',
        'description',
        'icon',
        'color',
        'points',
        'earned_at',
        'awarded_by',
        'is_public',
    ];

    protected $casts = [
        'points' => 'integer',
        'earned_at' => 'date',
        'is_public' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function certification(): BelongsTo
    {
        return $this->belongsTo(Certification::class);
    }

    public function awardedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'awarded_by');
    }
}
