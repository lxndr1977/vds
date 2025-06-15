<?php

namespace App\Services;

use App\Models\Choreography;
use App\Models\School;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class ChoreographyService
{
    public function createChoreography(array $data): Choreography
    {
        DB::beginTransaction();
        
        try {
            $choreography = Choreography::create($data);
            
            DB::commit();
            
            return $choreography;
            
        } catch (\Exception $e) {
            DB::rollBack();
            dd($e);
            
            Log::error('Error creating choreography', [
                'user_id' => Auth::id(),
                'school_id' => $data['school_id'] ?? null,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            throw new \Exception('Erro ao criar coreografia. Tente novamente.');
        }
    }

    public function updateChoreography(int $choreographyId, int $schoolId, array $data): Choreography
    {
        DB::beginTransaction();
        
        try {
            $choreography = Choreography::where('id', $choreographyId)
                ->where('school_id', $schoolId)
                ->firstOrFail();
            
            $choreography->update($data);
            
            DB::commit();
            
            Log::info('Choreography updated successfully', [
                'user_id' => Auth::id(),
                'choreography_id' => $choreography->id,
                'school_id' => $schoolId
            ]);
            
            return $choreography;
            
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            DB::rollBack();
            throw new \Exception('Coreografia não encontrada.');
            
        } catch (\Exception $e) {
            DB::rollBack();
            
            Log::error('Error updating choreography', [
                'user_id' => Auth::id(),
                'choreography_id' => $choreographyId,
                'school_id' => $schoolId,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            throw new \Exception('Erro ao atualizar coreografia. Tente novamente.');
        }
    }

    public function deleteChoreography(int $choreographyId, int $schoolId): void
    {
        DB::beginTransaction();
        
        try {
            $choreography = Choreography::where('id', $choreographyId)
                ->where('school_id', $schoolId)
                ->firstOrFail();
            
            // Verificar se há dançarinos vinculados
            if ($choreography->dancers()->exists()) {
                throw new \Exception('Não é possível excluir esta coreografia pois existem dançarinos vinculados a ela.');
            }
            
            $choreography->delete();
            
            DB::commit();
            
            Log::info('Choreography deleted successfully', [
                'user_id' => Auth::id(),
                'choreography_id' => $choreographyId,
                'school_id' => $schoolId
            ]);
            
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            DB::rollBack();
            throw new \Exception('Coreografia não encontrada.');
            
        } catch (\Exception $e) {
            DB::rollBack();
            
            Log::error('Error deleting choreography', [
                'user_id' => Auth::id(),
                'choreography_id' => $choreographyId,
                'school_id' => $schoolId,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            if (str_contains($e->getMessage(), 'dançarinos vinculados')) {
                throw $e;
            }
            
            throw new \Exception('Erro ao excluir coreografia. Tente novamente.');
        }
    }

    public function getChoreographiesBySchool(int $schoolId): array
    {
        try {
            $school = School::select('id', 'name')->find($schoolId);

            if (!$school) {
                return [];
            }

            // Otimização: carregar apenas os campos necessários
            $school->load([
                'choreographies:id,school_id,choreography_type_id,choreography_category_id,dance_style_id,name,music,music_composer,duration',
                'choreographies.choreographyType:id,name,min_dancers,max_dancers',
                'choreographies.choreographyCategory:id,name', 
                'choreographies.danceStyle:id,name',
                'choreographies.dancers:id,name',
                'choreographies.choreographers:id,choreography_id,name',
            ]);
            
            return $school->choreographies->toArray();
            
        } catch (\Exception $e) {
            Log::error('Error fetching choreographies', [
                'user_id' => Auth::id(),
                'school_id' => $schoolId,
                'error' => $e->getMessage()
            ]);
            
            return [];
        }
    }

    public function findChoreographyBySchool(int $choreographyId, int $schoolId): ?Choreography
    {
        try {
            return Choreography::where('id', $choreographyId)
                ->where('school_id', $schoolId)
                ->first();
                
        } catch (\Exception $e) {
            Log::error('Error finding choreography', [
                'user_id' => Auth::id(),
                'choreography_id' => $choreographyId,
                'school_id' => $schoolId,
                'error' => $e->getMessage()
            ]);
            
            return null;
        }
    }

    public function validateChoreographiesForStep(array $choreographies): array
    {
        $errors = [];
        
        // Validar se há pelo menos uma coreografia
        if (empty($choreographies)) {
            $errors[] = 'Adicione pelo menos uma coreografia antes de continuar.';
            return $errors;
        }

        // Validação adicional: verificar se todas as coreografias têm pelo menos um dançarino
        $choreographiesWithoutDancers = collect($choreographies)
            ->filter(function($choreography) {
                return empty($choreography['dancers']) || count($choreography['dancers']) == 0;
            });

        if ($choreographiesWithoutDancers->count() > 0) {
            $errors[] = 'Todas as coreografias devem ter pelo menos um dançarino atribuído.';
        }

        return $errors;
    }


    // Novo método para paginação manual
    public function getChoreographiesBySchoolPaginated(int $schoolId, int $perPage = 10, int $currentPage = 1): array
    {
        $offset = ($currentPage - 1) * $perPage;
        
        $query = Choreography::whereHas('school', function ($q) {
                $q->where('user_id', Auth::id());
            })
            ->where('school_id', $schoolId)
            ->orderBy('name', 'asc');
        
        // Pega o total de registros
        $total = $query->count();
        
        // Pega os registros da página atual
        $choreographies = $query->skip($offset)
            ->take($perPage)
            ->get();
        
        // Calcula se há mais páginas
        $totalLoaded = $offset + $choreographies->count();
        $hasMorePages = $totalLoaded < $total;
        
        // Log::info('Paginação Debug:', [
        //     'current_page' => $currentPage,
        //     'per_page' => $perPage,
        //     'offset' => $offset,
        //     'total' => $total,
        //     'loaded_this_page' => $choreographies->count(),
        //     'total_loaded' => $totalLoaded,
        //     'has_more_pages' => $hasMorePages
        // ]);
        
        return [
            'data' => $choreographies,
            'total' => $total,
            'per_page' => $perPage,
            'current_page' => $currentPage,
            'has_more_pages' => $hasMorePages,
            'from' => $offset + 1,
            'to' => min($offset + $perPage, $total)
        ];
    }
}