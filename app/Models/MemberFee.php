<?php

namespace App\Models;

use App\Models\MemberType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MemberFee extends Model
{
    protected $fillable = ['member_type_id', 'amount', 'valid_until'];

    protected $casts = [
        'valid_until' => 'date',
        'amount' => 'decimal:2'
    ];

    public function memberType(): BelongsTo
    {
        return $this->belongsTo(MemberType::class);
    }
}
