<?php
namespace App\Services;

use App\Models\Member;
use App\Models\MemberType;
use App\Models\School;

class MemberValidationService
{
    /**
     * Valida se é possível adicionar/editar um membro com o tipo especificado
     */
    public function validateMemberTypeLimit(
        int $schoolId,
        int $memberTypeId,
        ?int $excludeMemberId = null
    ): array {
        $memberType = MemberType::find($memberTypeId);
        $school = School::find($schoolId);

        if (!$memberType || !$school) {
            return [
                'valid' => false,
                'message' => 'Tipo de membro ou escola não encontrados.'
            ];
        }

        // Se não há limite, está válido
        if ($memberType->max_limit === null) {
            return ['valid' => true];
        }

        $currentCount = $school->members()
            ->where('member_type_id', $memberTypeId)
            ->when($excludeMemberId, fn($query) => $query->where('id', '!=', $excludeMemberId))
            ->count();

        if ($currentCount >= $memberType->max_limit) {
            return [
                'valid' => false,
                'message' => "Limite máximo de {$memberType->max_limit} integrante(s) para o tipo '{$memberType->name}' atingido.",
                'current_count' => $currentCount,
                'max_limit' => $memberType->max_limit
            ];
        }

        return [
            'valid' => true,
            'current_count' => $currentCount,
            'max_limit' => $memberType->max_limit,
            'remaining' => $memberType->max_limit - $currentCount
        ];
    }

    /**
     * Método helper para usar em validações do Laravel
     */
    public static function memberTypeLimitRule(int $schoolId, ?int $excludeMemberId = null): \Closure
    {
        return function (string $attribute, mixed $value, \Closure $fail) use ($schoolId, $excludeMemberId) {
            $service = new self();
            $validation = $service->validateMemberTypeLimit($schoolId, $value, $excludeMemberId);
            
            if (!$validation['valid']) {
                $fail($validation['message']);
            }
        };
    }
}