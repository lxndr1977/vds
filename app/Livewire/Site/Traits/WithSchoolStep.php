<?php

namespace App\Livewire\Site\Traits;

use App\Models\Registration;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

trait WithSchoolStep
{
    // Estado para os dados da escola
    public array $schoolState = [
        'name' => '',
        'is_social_project' => false,
        'is_university_project' => false,
        'street' => '',
        'number' => '',
        'complement' => '',
        'district' => '',
        'city' => '',
        'state' => '',
        'zip_code' => '',
    ];

    /**
     * Inicializa os dados da etapa da escola.
     *
     * @return void
     */
    public function mountSchoolStep()
    {
        // Preenche o estado com os dados da escola, se já existirem
        if ($this->school->exists) {
            $this->schoolState = $this->school->toArray();
        }
    }

    /**
     * Validação para os dados da escola.
     *
     * @return array
     */
    protected function schoolRules(): array
    {
        return [
            'schoolState.name' => ['required', 'min:3', 'max:255'],
            'schoolState.is_social_project' => ['boolean'],
            'schoolState.is_university_project' => ['boolean'],
            'schoolState.street' => ['required', 'string', 'max:255'],
            'schoolState.number' => ['required', 'string', 'max:50'],
            'schoolState.complement' => ['required', 'string', 'max:100'],
            'schoolState.district' => ['required', 'string', 'max:100'],
            'schoolState.city' => ['required', 'string', 'max:100'],
            'schoolState.state' => ['required', 'string', 'max:2'],
            'schoolState.zip_code' => ['required', 'string', 'max:10'],
        ];
    }

    protected function schoolMessages(): array
    {
        return [
            'schoolState.name.required' => 'O nome da escola é obrigatório.',
            'schoolState.name.min' => 'O nome da escola deve ter no mínimo 3 caracteres.',
            'schoolState.name.max' => 'O nome da escola não pode ter mais de 255 caracteres.',

            'schoolState.is_social_project.boolean' => 'O campo "Projeto Social" deve ser verdadeiro ou falso.',
            'schoolState.is_university_project.boolean' => 'O campo "Projeto Universitário" deve ser verdadeiro ou falso.',

            'schoolState.street.required' => 'A rua é obrigatória.',
            'schoolState.street.string' => 'A rua deve ser um texto.',
            'schoolState.street.max' => 'A rua não pode ter mais de 255 caracteres.',

            'schoolState.number.required' => 'O número é obrigatório.',
            'schoolState.number.string' => 'O número deve ser um texto.',
            'schoolState.number.max' => 'O número não pode ter mais de 50 caracteres.',

            'schoolState.complement.required' => 'O complemento é obrigatório.',
            'schoolState.complement.string' => 'O complemento deve ser um texto.',
            'schoolState.complement.max' => 'O complemento não pode ter mais de 100 caracteres.',

            'schoolState.district.required' => 'O bairro é obrigatório.',
            'schoolState.district.string' => 'O bairro deve ser um texto.',
            'schoolState.district.max' => 'O bairro não pode ter mais de 100 caracteres.',

            'schoolState.city.required' => 'A cidade é obrigatória.',
            'schoolState.city.string' => 'A cidade deve ser um texto.',
            'schoolState.city.max' => 'A cidade não pode ter mais de 100 caracteres.',

            'schoolState.state.required' => 'O estado (UF) é obrigatório.',
            'schoolState.state.string' => 'O estado deve ser um texto.',
            'schoolState.state.max' => 'O estado deve ter no máximo 2 caracteres.',

            'schoolState.zip_code.required' => 'O CEP é obrigatório.',
            'schoolState.zip_code.string' => 'O CEP deve ser um texto.',
            'schoolState.zip_code.max' => 'O CEP não pode ter mais de 10 caracteres.',
        ];
    }


    /**
     * Salva os dados da escola e a inscrição, e avança para a próxima etapa.
     *
     * @return void
     */
    public function saveSchool()
    {
        $this->validate(
            $this->schoolRules(),
            $this->schoolMessages()
        );

        DB::transaction(function () {
            // Associa o user_id do usuário autenticado
            $this->school->user_id = auth()->id();
            
            // Preenche os dados da escola a partir do estado do formulário
            $this->school->fill($this->schoolState);
            $this->school->save();

            // Cria ou atualiza a inscrição associada à escola
            $this->registration = Registration::firstOrCreate(
                ['school_id' => $this->school->id],
                ['status_registration' => 'draft']
            );
        });
        
        $this->success(title: 'Atualizado', icon: 'o-check-circle', description:'Dados da escola atualizados com sucesso');
        $this->nextStep();
    }

    public function clearError($field)
    {
        $this->resetErrorBag($field);
    }

}
