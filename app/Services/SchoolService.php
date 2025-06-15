<?php

namespace App\Services;

use Throwable; 
use App\Models\User; 
use App\Models\School;
use App\Models\Registration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Gate; 

class SchoolService {
    public function createSchoolWithRegistration(array $schoolData, User $user): ?School
    {
        if (Gate::denies('create', School::class)) {
            throw new \Illuminate\Auth\Access\AuthorizationException('Você não tem permissão para criar escolas.');
        }

        DB::beginTransaction();
        try {
            $schoolData['user_id'] = $user->id;

            $school = School::create($schoolData);

            Registration::create([
                'school_id' => $school->id,
                'status_registration' => 'draft',
            ]);

            DB::commit(); 
            return $school;
        } catch (Throwable $e) {
            DB::rollBack(); 
            Log::error("Erro ao criar escola e registro: " . $e->getMessage());
            return null;
        }
    }

    public function updateSchool(School $school, array $data, User $user): bool
    {
        if (Gate::denies('update', $school)) {
            throw new \Illuminate\Auth\Access\AuthorizationException('Você não tem permissão para atualizar esta escola.');
        }
        return $school->update($data);
    }

    public function deleteSchool(School $school, User $user): bool
    {
        if (Gate::denies('delete', $school)) {
            throw new \Illuminate\Auth\Access\AuthorizationException('Você não tem permissão para deletar esta escola.');
        }

        return $school->delete();
    }

    public function getSchoolById(int $id): ?School
    {
        return School::find($id);
    }
    public function getAllSchools()
    {
        return School::all();
    }

    public function getUserSchool(User $user)
    {
        return $user->schools()->first();
    }

}