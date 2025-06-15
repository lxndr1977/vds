<?php

namespace App\Livewire\Site\Traits;

use App\Models\Choreographer;
use Illuminate\Database\Eloquent\Collection;

trait WithChoreographersStep
{
    public Collection $choreographers;
    
    public bool $choreographerModal = false;
    public bool $confirmDeleteChoreographerModal = false;

    // Estado para o formulário de adição de novo coreógrafo
    public array $choreographerState = [
        'name' => '',
    ]; 

    // Estados para edição
    public bool $isEditingChoreographer = false;
    public ?int $editingChoreographerId = null;
    public ?int $choreographerToDelete = null;

    /**
     * Inicializa os dados da etapa de coreógrafos.
     *
     * @return void
     */
    public function mountChoreographersStep()
    {
        $this->loadChoreographers();
    }

    /**
     * Carrega os coreógrafos associados à escola.
     * 
     * @return void
     */
    public function loadChoreographers()
    {
        $this->choreographers = $this->school->exists ? $this->school->choreographers()->get() : new Collection();
    }

    /**
     * Validação para um novo coreógrafo.
     *
     * @return array
     */
    protected function choreographerRules(): array
    {
        return [
            'choreographerState.name' => ['required', 'string', 'max:255'],
        ];
    }

    protected function choreographerMessages(): array
    {
        return [
            'choreographerState.name.required' => 'O nome é obrigatório.',
            'choreographerState.name.string' => 'O nome deve ser um texto.',
            'choreographerState.name.max' => 'O nome não pode ter mais de 255 caracteres.',
        ];
    }
    /**
     * Adiciona um novo coreógrafo à escola.
     *
     * @return void
     */
    public function addChoreographer()
    {
        $this->validate(
            $this->choreographerRules(),
            $this->choreographerMessages()
        );

        $this->school->choreographers()->create($this->choreographerState);

        $this->closeChoreographerModal();

        $this->success(title: 'Adicionado', icon: 'o-check-circle', description:'Coreógrafo adicionado com sucesso');
        $this->reset('choreographerState');
        $this->loadChoreographers();
    }

    /**
     * Abre o modal para editar um coreógrafo existente.
     *
     * @param int $choreographerId
     * @return void
     */
    public function editChoreographer(int $choreographerId)
    {
        $choreographer = $this->school->choreographers()->find($choreographerId);
        
        if ($choreographer) {
            $this->isEditingChoreographer = true;
            $this->editingChoreographerId = $choreographerId;
            
            // Preenche o estado com os dados do coreógrafo
            $this->choreographerState = [
                'name' => $choreographer->name,
            ];
            
            $this->openChoreographerModal();
        }
    }

    /**
     * Atualiza um coreógrafo existente.
     *
     * @return void
     */
    public function updateChoreographer()
    {
        $this->validate(
            $this->choreographerRules(),
            $this->choreographerMessages()
        );

        $choreographer = $this->school->choreographers()->find($this->editingChoreographerId);
        
        if ($choreographer) {
            $choreographer->update($this->choreographerState);
            
            $this->closeChoreographerModal();
            $this->success(title: 'Atualizado', icon: 'o-check-circle', description:'Coreógrafo adicionado com sucesso');
            $this->loadChoreographers();
        }
    }

    /**
     * Prepara a exclusão de um coreógrafo (abre o modal de confirmação).
     *
     * @param int $choreographerId
     * @return void
     */
    public function prepareDeleteChoreographer(int $choreographerId)
    {
        $this->choreographerToDelete = $choreographerId;
        $this->confirmDeleteChoreographerModal = true;
    }

    /**
     * Confirma e executa a remoção do coreógrafo.
     *
     * @return void
     */
    public function confirmRemoveChoreographer()
    {
        if ($this->choreographerToDelete) {
            $this->removeChoreographer($this->choreographerToDelete);
            $this->confirmDeleteChoreographerModal = false;
            $this->choreographerToDelete = null;
        }
    }

    /**
     * Remove um coreógrafo.
     *
     * @param int $choreographerId
     * @return void
     */
   public function removeChoreographer(int $choreographerId)
    {
        $choreographer = $this->school->choreographers()->find($choreographerId);

        if ($choreographer) {
            try {
                $choreographer->delete();
                $this->success(title: 'Excluído', icon: 'o-check-circle', description:'Coreógrafo excluído com sucesso');
                $this->loadChoreographers();
            } catch (\Exception $e) {
                $this->error(title: 'Erro', icon: 'o-information-circle', description: $e->getMessage());
            }
        }
    }

    public function openChoreographerModal()
    {
        return $this->choreographerModal = true;
    }

    public function closeChoreographerModal()
    {
        $this->choreographerModal = false;
        $this->isEditingChoreographer = false;
        $this->editingChoreographerId = null;
        $this->reset('choreographerState');
        $this->resetValidation();
    }
}