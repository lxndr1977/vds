<?php

namespace App\Services;

use App\Models\Member;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class MemberService 
{
    public function createMember(array $memberData): Member
    {
        Gate::authorize('create', Member::class);
        
        Log::info('Criando novo membro:', [
            'user_id' => Auth::id(),
            'school_id' => $memberData['school_id'] ?? null
        ]);
        
        return Member::create($memberData);
    }

    public function updateMember(int $memberId, array $data): Member
    {
        $member = Member::findOrFail($memberId);
        Gate::authorize('update', $member);
        
        Log::info('Atualizando membro:', [
            'member_id' => $memberId,
            'user_id' => Auth::id()
        ]);
        
        $member->update($data);
        return $member->fresh();
    }

    public function deleteMember(int $memberId): bool
    {
        $member = Member::findOrFail($memberId);
        Gate::authorize('delete', $member);
        
        Log::info('Excluindo membro:', [
            'member_id' => $memberId,
            'user_id' => Auth::id()
        ]);
        
        return $member->delete();
    }

    public function getMemberById(int $id): Member
    {
        $member = Member::findOrFail($id);
        Gate::authorize('view', $member);
        
        return $member;
    }

    public function getMembersBySchool(int $schoolId): Collection
    {
        return Member::whereHas('school', function ($query) {
                $query->where('user_id', Auth::id());
            })
            ->where('school_id', $schoolId)
            ->orderBy('name', 'asc')
            ->get();
    }

    // Novo método para paginação manual
    public function getMembersBySchoolPaginated(int $schoolId, int $perPage = 10, int $currentPage = 1): array
    {
        $offset = ($currentPage - 1) * $perPage;
        
        $query = Member::whereHas('school', function ($q) {
                $q->where('user_id', Auth::id());
            })
            ->where('school_id', $schoolId)
            ->orderBy('name', 'asc');
        
        // Pega o total de registros
        $total = $query->count();
        
        // Pega os registros da página atual
        $members = $query->skip($offset)
            ->take($perPage)
            ->get();
        
        // Calcula se há mais páginas
        $totalLoaded = $offset + $members->count();
        $hasMorePages = $totalLoaded < $total;
        
        Log::info('Paginação Debug:', [
            'current_page' => $currentPage,
            'per_page' => $perPage,
            'offset' => $offset,
            'total' => $total,
            'loaded_this_page' => $members->count(),
            'total_loaded' => $totalLoaded,
            'has_more_pages' => $hasMorePages
        ]);
        
        return [
            'data' => $members,
            'total' => $total,
            'per_page' => $perPage,
            'current_page' => $currentPage,
            'has_more_pages' => $hasMorePages,
            'from' => $offset + 1,
            'to' => min($offset + $perPage, $total)
        ];
    }

    public function getAllMembers(): Collection
    {
        Gate::authorize('viewAny', Member::class);
        
        return Member::with('school')
            ->orderBy('name', 'asc')
            ->get();
    }

    public function getMembersByUser(): Collection
    {
        return Member::whereHas('school', function ($query) {
                $query->where('user_id', Auth::id());
            })
            ->orderBy('name', 'asc')
            ->get();
    }

    public function searchMembers(string $search, ?int $schoolId = null): Collection
    {
        $query = Member::where('name', 'like', "%{$search}%")
            ->orWhere('email', 'like', "%{$search}%");

        if ($schoolId) {
            $query->where('school_id', $schoolId);
        }

        $query->whereHas('school', function ($subQuery) {
            $subQuery->where('user_id', Auth::id());
        });

        return $query->orderBy('name', 'asc')->get();
    }

    // Método para busca com paginação
    public function searchMembersPaginated(string $search, ?int $schoolId = null, int $perPage = 10, int $currentPage = 1): array
    {
        $offset = ($currentPage - 1) * $perPage;
        
        $query = Member::where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });

        if ($schoolId) {
            $query->where('school_id', $schoolId);
        }

        $query->whereHas('school', function ($subQuery) {
            $subQuery->where('user_id', Auth::id());
        });

        $total = $query->count();
        
        $members = $query->skip($offset)
            ->take($perPage)
            ->orderBy('name', 'asc')
            ->get();
        
        $hasMorePages = ($offset + $perPage) < $total;
        
        return [
            'data' => $members,
            'total' => $total,
            'per_page' => $perPage,
            'current_page' => $currentPage,
            'has_more_pages' => $hasMorePages,
            'from' => $offset + 1,
            'to' => min($offset + $perPage, $total)
        ];
    }
}