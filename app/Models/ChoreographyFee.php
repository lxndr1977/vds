<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ChoreographyFee extends Model
{
    protected $fillable = ['choreography_type_id', 'amount', 'valid_until'];

    protected $casts = [
        'valid_until' => 'date',
        'amount' => 'decimal:2',
    ];

    public function choreographyType(): BelongsTo
    {
        return $this->belongsTo(ChoreographyType::class);
    }
}
