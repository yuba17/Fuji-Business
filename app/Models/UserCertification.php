<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class UserCertification extends Model
{
    protected $fillable = [
        'user_id',
        'certification_id',
        'certification_plan_id',
        'assigned_from_plan_at',
        'obtained_at',
        'expires_at',
        'certificate_number',
        'status',
        'planned_date',
        'priority',
        'notes',
        'approved_by',
        'approved_at',
    ];

    protected $casts = [
        'assigned_from_plan_at' => 'datetime',
        'obtained_at' => 'date',
        'expires_at' => 'date',
        'planned_date' => 'date',
        'priority' => 'integer',
        'approved_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function certification(): BelongsTo
    {
        return $this->belongsTo(Certification::class);
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    /**
     * Plan de certificaciones del que proviene esta certificación
     */
    public function certificationPlan(): BelongsTo
    {
        return $this->belongsTo(CertificationPlan::class);
    }

    public function getIsExpiredAttribute(): bool
    {
        if (!$this->expires_at) {
            return false;
        }
        return $this->expires_at->isPast();
    }

    public function getDaysUntilExpiryAttribute(): ?int
    {
        if (!$this->expires_at) {
            return null;
        }
        return now()->diffInDays($this->expires_at, false);
    }

    public function getExpiryStatusAttribute(): string
    {
        if (!$this->expires_at) {
            return 'permanent';
        }
        
        $days = $this->days_until_expiry;
        
        if ($days < 0) {
            return 'expired';
        } elseif ($days <= 30) {
            return 'expiring_soon';
        } elseif ($days <= 90) {
            return 'expiring_later';
        }
        
        return 'valid';
    }
}
