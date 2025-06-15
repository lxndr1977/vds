<?php

namespace App\Services;

use App\Models\Dancer;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class DancerService 
{ 
    public function createDancer(array $dancerData): Dancer
    {
        Gate::authorize('create', Dancer::class);
        
        Log::info('Criando novo dançarino:', [
            'user_id' => Auth::id(),
            'school_id' => $dancerData['school_id'] ?? null
        ]);
        
        return Dancer::create($dancerData);
    }

     public function updateDancer(int $memberId, array $data): Dancer
    {
        $member = Dancer::findOrFail($memberId);
        Gate::authorize('update', $member);
        
        Log::info('Atualizando dançarino:', [
            'dancer_id' => $memberId,
            'user_id' => Auth::id()
        ]);
        
        $member->update($data);
        return $member->fresh();
    }

    public function deleteDancer(int $memberId): bool
    {
        $member = Dancer::findOrFail($memberId);
        Gate::authorize('delete', $member);
        
        Log::info('Excluindo dançarino:', [
            'dancer_id' => $memberId,
            'user_id' => Auth::id()
        ]);
        
        return $member->delete();
    }


    // Novo método para paginação manual
    public function getDancersBySchoolPaginated(int $schoolId, int $perPage = 10, int $currentPage = 1): array
    {
        $offset = ($currentPage - 1) * $perPage;
        
        $query = Dancer::whereHas('school', function ($q) {
                $q->where('user_id', Auth::id());
            })
            ->where('school_id', $schoolId)
            ->orderBy('name', 'asc');
        
        // Pega o total de registros
        $total = $query->count();
        
        // Pega os registros da página atual
        $dancers = $query->skip($offset)
            ->take($perPage)
            ->get();
        
        // Calcula se há mais páginas
        $totalLoaded = $offset + $dancers->count();
        $hasMorePages = $totalLoaded < $total;
        
        Log::info('Paginação Debug:', [
            'current_page' => $currentPage,
            'per_page' => $perPage,
            'offset' => $offset,
            'total' => $total,
            'loaded_this_page' => $dancers->count(),
            'total_loaded' => $totalLoaded,
            'has_more_pages' => $hasMorePages
        ]);
        
        return [
            'data' => $dancers,
            'total' => $total,
            'per_page' => $perPage,
            'current_page' => $currentPage,
            'has_more_pages' => $hasMorePages,
            'from' => $offset + 1,
            'to' => min($offset + $perPage, $total)
        ];
    }

}