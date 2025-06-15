<?php

namespace App\Services;

use App\Models\Choreography;
use App\Models\Dancer;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class ChoreographyDancerService
{
    public function assignDancersToChoreography(int $choreographyId, int $schoolId, array $dancerIds): void
    {
        DB::beginTransaction();
        
        try {
            $choreography = Choreography::with('choreographyType')
                ->where('id', $choreographyId)
                ->where('school_id', $schoolId)
                ->firstOrFail();

            $choreography->syncDancersWithValidation($dancerIds);

            DB::commit();
            
            Log::info('Dancers assigned to choreography successfully', [
                'user_id' => Auth::id(),
                'choreography_id' => $choreographyId,
                'school_id' => $schoolId,
                'dancer_count' => count($dancerIds)
            ]);
            
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            DB::rollBack();
            throw new \Exception('Coreografia não encontrada.');
            
        } catch (\Exception $e) {
            DB::rollBack();
            
            Log::error('Error assigning dancers to choreography', [
                'user_id' => Auth::id(),
                'choreography_id' => $choreographyId,
                'school_id' => $schoolId,
                'dancer_ids' => $dancerIds,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            throw $e;
        }
    }

    public function removeDancerFromChoreography(int $choreographyId, int $dancerId, int $schoolId): void
    {
        DB::beginTransaction();
        
        try {
            $choreography = Choreography::where('id', $choreographyId)
                ->where('school_id', $schoolId)
                ->firstOrFail();

            // Verificar se o dançarino pertence à escola
            $dancer = Dancer::where('id', $dancerId)
                ->where('school_id', $schoolId)
                ->firstOrFail();

            $choreography->dancers()->detach($dancerId);
            
            DB::commit();
            
            Log::info('Dancer removed from choreography successfully', [
                'user_id' => Auth::id(),
                'choreography_id' => $choreographyId,
                'dancer_id' => $dancerId,
                'school_id' => $schoolId
            ]);
            
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            DB::rollBack();
            
            if (str_contains($e->getModel(), 'Choreography')) {
                throw new \Exception('Coreografia não encontrada.');
            } else {
                throw new \Exception('Dançarino não encontrado.');
            }
            
        } catch (\Exception $e) {
            DB::rollBack();
            
            Log::error('Error removing dancer from choreography', [
                'user_id' => Auth::id(),
                'choreography_id' => $choreographyId,
                'dancer_id' => $dancerId,
                'school_id' => $schoolId,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            throw new \Exception('Erro ao remover dançarino. Tente novamente.');
        }
    }

    public function getAssignedDancers(int $choreographyId, int $schoolId): array
    {
        try {
            $choreography = Choreography::with('dancers:id,name')
                ->where('id', $choreographyId)
                ->where('school_id', $schoolId)
                ->first();

            if (!$choreography) {
                return [];
            }

            return $choreography->dancers->pluck('id')->map(fn($id) => (string) $id)->toArray();
            
        } catch (\Exception $e) {
            Log::error('Error fetching assigned dancers', [
                'user_id' => Auth::id(),
                'choreography_id' => $choreographyId,
                'school_id' => $schoolId,
                'error' => $e->getMessage()
            ]);
            
            return [];
        }
    }

    public function getAvailableDancers(int $schoolId): array
    {
        try {
            return Dancer::select('id', 'name')
                ->where('school_id', $schoolId)
                ->orderBy('name')
                ->get()
                ->toArray();
                
        } catch (\Exception $e) {
            Log::error('Error fetching available dancers', [
                'user_id' => Auth::id(),
                'school_id' => $schoolId,
                'error' => $e->getMessage()
            ]);
            
            return [];
        }
    }

    public function validateDancerAssignment(array $dancerIds, int $schoolId): void
    {
        if (empty($dancerIds)) {
            return;
        }

        // Validar se todos os dançarinos pertencem à escola
        $validDancers = Dancer::whereIn('id', $dancerIds)
            ->where('school_id', $schoolId)
            ->count();

        if ($validDancers !== count($dancerIds)) {
            throw new \Exception('Um ou mais dançarinos selecionados são inválidos.');
        }
    }
}