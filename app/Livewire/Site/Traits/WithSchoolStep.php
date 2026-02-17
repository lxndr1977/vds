<?php

namespace App\Livewire\Site\Traits;

use App\Enums\BrazilStateEnum;
use App\Models\Registration;
use App\Services\ZipCodeService;
use Illuminate\Support\Facades\DB;

trait WithSchoolStep
{
    public $brazilStates;

    // Propriedade para controlar a exibição do modal
    public $showExplanationModal = false;

    // Estado para os dados da escola
    public array $schoolState = [
        'name' => '',
        'street' => '',
        'number' => '',
        'complement' => '',
        'district' => '',
        'city' => '',
        'state' => '',
        'zip_code' => '',
        'responsible_name' => '',
        'responsible_email' => '',
        'responsible_phone' => '',
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

        $this->brazilStates = BrazilStateEnum::toArray();

        // Verifica se deve mostrar o modal explicativo
        $this->checkShowExplanationModal();
    }

    /**
     * Verifica se deve mostrar o modal explicativo baseado no cookie
     *
     * @return void
     */
    private function checkShowExplanationModal()
    {
        // Verifica se o cookie existe e se ainda é válido
        $lastShown = request()->cookie('school_step_modal_shown');

        if (! $lastShown || now()->diffInDays($lastShown) >= 2) {
            $this->showExplanationModal = true;
        }
    }

    /**
     * Fecha o modal e define o cookie
     *
     * @return void
     */
    public function closeExplanationModal()
    {
        $this->showExplanationModal = false;

        // Define o cookie para 2 dias
        cookie()->queue('school_step_modal_shown', now()->toDateString(), 60 * 24 * 2);
    }

    /**
     * Validação para os dados da escola.
     */
    protected function schoolRules(): array
    {
        return [
            'schoolState.name' => ['required', 'min:3', 'max:255'],
            'schoolState.is_social_project' => ['boolean'],
            'schoolState.is_university_project' => ['boolean'],
            'schoolState.street' => ['required', 'string', 'max:255'],
            'schoolState.number' => ['required', 'string', 'max:50'],
            'schoolState.complement' => ['string', 'max:100'],
            'schoolState.district' => ['required', 'string', 'max:100'],
            'schoolState.city' => ['required', 'string', 'max:100'],
            'schoolState.state' => ['required', 'string', 'max:2'],
            'schoolState.zip_code' => ['required', 'string', 'max:9'],
            'schoolState.responsible_name' => ['required', 'string', 'min:3'],
            'schoolState.responsible_email' => ['required', 'email', 'max:255'],
            'schoolState.responsible_phone' => ['required', 'max:20'],
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
            'schoolState.zip_code.max' => 'O CEP não pode ter mais de 9 caracteres.',

            'schoolState.responsible_name.required' => 'O nome do responsavel é obrigatório.',
            'schoolState.responsible_name.min' => 'O nome do responsável deve ter no mínimo 3 caracteres.',
            'schoolState.responsible_name.max' => 'O nome do responsável não pode ter mais de 255 caracteres.',

            'schoolState.responsible_email.required' => 'O email do responsavel é obrigatório.',
            'schoolState.responsible_email.email' => 'Informe um e-mail válido.',
            'schoolState.responsible_email.max' => 'O email do responsável não pode ter mais de 255 caracteres.',

            'schoolState.responsible_phone.string' => 'O telefone deve ser um texto.',
            'schoolState.responsible_phone.max' => 'O telefone não pode ter mais de 20 caracteres.',
        ];
    }

    /**
     * Salva os dados da escola e a inscrição, e avança para a próxima etapa.
     *
     * @return void
     */
    /**
     * Salva os dados da escola e a inscrição, e avança para a próxima etapa.
     *
     * @return void
     */
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

        $wasSaved = false;

        DB::transaction(function () use (&$wasSaved) {

            // Verifica se há mudanças antes de salvar
            $this->school->fill($this->schoolState);
            $this->school->user_id = auth()->id();

            // Salva se for novo registro OU se houver mudanças
            if (! $this->school->exists || $this->school->isDirty()) {
                $this->school->save();
                $wasSaved = true;
            }

            // Cria ou atualiza a inscrição associada à escola
            $this->registration = Registration::firstOrCreate(
                ['school_id' => $this->school->id],
                ['status_registration' => 'draft']
            );
        });

        if ($wasSaved) {
            $this->success(
                title: 'Atualizado',
                icon: 'o-check-circle',
                description: 'Dados da escola atualizados com sucesso',
                position: 'toast-top toast-right',
                css: 'bg-green-100 border-green-100 text-green-900 text-md');
        }

        $this->nextStep();
    }

    public function clearError($field)
    {
        $this->resetErrorBag($field);
    }

    public function searchZipCode()
    {
        try {
            $zipCodeService = app(ZipCodeService::class);
            $address = $zipCodeService->getAddressByZipCode($this->schoolState['zip_code']);

            if ($address) {
                $this->schoolState = array_merge($this->schoolState, $address);
            } else {
                $this->addError('schoolState.zip_code', 'CEP não encontrado.');
            }
        } catch (\Exception $e) {
            $this->addError('schoolState.zip_code', $e->getMessage());
        }
    }
}
