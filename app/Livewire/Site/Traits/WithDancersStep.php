<?php

namespace App\Livewire\Site\Traits;

use App\Models\Dancer;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Collection;

trait WithDancersStep
{
    public Collection $dancers;
    

    public bool $dancerModal = false;
    public bool $isEditingDancer = false;
    public int $editingDancerId = 0;
    public bool $deleteConfirmModal = false;
    public int $dancerToDelete = 0;

    // Estado para o formulário de adição de novo dançarino
    public array $dancerState = [
        'name' => '',
        'birth_date' => '',
    ];

    /**
     * Inicializa os dados da etapa de dançarinos.
     *
     * @return void
     */
    public function mountDancersStep()
    {
        $this->loadDancers();
    }

    /**
     * Carrega os dançarinos associados à escola.
     *
     * @return void
     */
    public function loadDancers()
    {
        $this->dancers = $this->school->exists ? $this->school->dancers()->get() : new Collection();
    }

    /**
     * Validação para um novo dançarino.
     *
     * @return array
     */
    protected function dancerRules(): array
    {
        return [
            'dancerState.name' => ['required', 'max:255'],
            'dancerState.birth_date' => [
                'required', 
                'date_format:d/m/Y',
                'before:today', 
                'after:1900-01-01' 
            ],
        ];
    }

protected function dancerMessages(): array
{
    return [
        'dancerState.name.required' => 'O nome do(a) bailarino(a) é obrigatório.',
        'dancerState.name.max' => 'O nome do(a) bailarino(a) não pode ter mais de 255 caracteres.',

        'dancerState.birth_date.required' => 'A data de nascimento é obrigatória.',
        'dancerState.birth_date.date_format' => 'A data de nascimento deve estar no formato dd/mm/aaaa.',
        'dancerState.birth_date.before' => 'A data de nascimento não pode ser no futuro.',
        'dancerState.birth_date.after' => 'A data de nascimento deve ser posterior a 1900.',
    ];
}

    /**
     * Adiciona um novo dançarino à escola.
     *
     * @return void
     */
    public function addDancer()
    {
        $this->validate(
            $this->dancerRules(),
            $this->dancerMessages()
        );

        $this->school->dancers()->create($this->dancerState);

        $this->closeDancerModal();

        $this->success( 
            icon: 'o-check-circle', 
            title: 'Bailarino(a) Adicionado(a)', 
            description:'As informações do(a) bailarino(a) foram adicionadas com sucesso',
            position: 'toast-top toast-right',
            css: "bg-green-100 border-green-100 text-green-900 text-md");

        $this->reset('dancerState');
        $this->loadDancers();
    }

    /**
     * Prepara o modal para edição de um dançarino.
     *
     * @param int $dancerId
     * @return void
     */
    public function editDancer(int $dancerId)
    {
        $dancer = $this->school->dancers()->find($dancerId);

        if ($dancer) {
            $this->isEditingDancer = true;
            $this->editingDancerId = $dancerId;
            $this->dancerState = [
                'name' => $dancer->name,
                'birth_date' => $dancer->birth_date,
            ];
            $this->openDancerModal();
        }
    }

    /**
     * Atualiza um dançarino existente.
     *
     * @return void
     */
    public function updateDancer()
    {
        $this->validate(
            $this->dancerRules(),
            $this->dancerMessages()
        );

        $dancer = $this->school->dancers()->find($this->editingDancerId);

        if ($dancer) {
            $dancer->update($this->dancerState);
            $this->closeDancerModal();
            $this->success(
                icon: 'o-check-circle', 
                title: 'Bailarino(a) Atualizado(a)', 
                description:'As informações do(a) bailarino(a) foram atualizadas com sucesso',
                position: 'toast-top toast-right',
                css: "bg-green-100 border-green-100 text-green-900 text-md");

            $this->resetDancerForm();
            $this->loadDancers();
        }
    }

    /**
     * Abre o modal de confirmação de exclusão.
     *
     * @param int $dancerId
     * @return void
     */
    public function openDeleteConfirm(int $dancerId)
    {
        $this->dancerToDelete = $dancerId;
        $this->deleteConfirmModal = true;
    }

    /**
     * Confirma e executa a remoção do dançarino.
     *
     * @return void
     */
    public function confirmRemoveDancer()
    {
        $this->removeDancer($this->dancerToDelete);
        $this->deleteConfirmModal = false;
        $this->dancerToDelete = 0;
    }

    /**
     * Remove um dançarino.
     *
     * @param int $dancerId
     * @return void
     */
    public function removeDancer(int $dancerId)
    {
        $dancer = $this->school->dancers()->find($dancerId);

        if ($dancer) {
            try {
                $dancer->delete();
                $this->success(
                    icon: 'o-check-circle', 
                    title: 'Bailarino(a) Excluído(a)', 
                    description:'As informações do(a) bailarino(a) foram excluídas com sucesso',
                    position: 'toast-top toast-right',
                    css: "bg-green-100 border-green-100 text-green-900 text-md");

                $this->loadDancers();
            } catch (\Exception $e) {
                $this->error(
                    icon: 'o-information-circle', 
                    title: "Erro", 
                    description: $e->getMessage(),
                    position: 'toast-top toast-right',
                    css: "bg-red-100 border-red-100 text-red-900 text-md");
            }
        }
    }

    /**
     * Reseta o formulário de dançarino.
     *
     * @return void
     */
    public function resetDancerForm()
    {
        $this->reset(['dancerState', 'isEditingDancer', 'editingDancerId', 'deleteConfirmModal', 'dancerToDelete']);
    }

    public function openDancerModal()
    {
                $this->resetValidation();

        return $this->dancerModal = true;
    }

    public function closeDancerModal()
    {
        $this->resetDancerForm();
        $this->resetValidation();
        return $this->dancerModal = false;
    }
}