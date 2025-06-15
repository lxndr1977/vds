<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChoreographyExtraFee extends Model
{
protected $fillable = [
        'description',
        'value',
        'active',
    ];

    protected $casts = [
        'value' => 'decimal:2',
        'active' => 'boolean',
    ];

    /**
     * Scope para buscar apenas taxas ativas
     */
    public function scopeActive($query)
    {
        return $query->where('active', true);
    }

    /**
     * Método estático para calcular total de taxas extras
     * baseado na quantidade de coreografias
     */
    public static function calculateTotalFees(int $choreographyCount): array
    {
        $activeFees = self::active()->get();
        $totalFees = 0;
        $feeDetails = [];

        foreach ($activeFees as $fee) {
            $feeTotal = $fee->value * $choreographyCount;
            $totalFees += $feeTotal;
            
            $feeDetails[] = [
                'description' => $fee->description,
                'value_per_choreography' => $fee->value,
                'choreography_count' => $choreographyCount,
                'total' => $feeTotal,
            ];
        }

        return [
            'fees' => $feeDetails,
            'total' => $totalFees,
        ];
    }
}
