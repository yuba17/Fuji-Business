<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserInternalRole extends Model
{
    protected $fillable = [
        'user_id',
        'internal_role_id',
        'from_date',
        'to_date',
        'reason',
    ];

    protected function casts(): array
    {
        return [
            'from_date' => 'date',
            'to_date' => 'date',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function internalRole(): BelongsTo
    {
        return $this->belongsTo(InternalRole::class);
    }
}


