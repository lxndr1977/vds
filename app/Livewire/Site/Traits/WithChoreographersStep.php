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
        'is_public_domain' => false,
        'is_adaptation' => false, 
        'is_attending' => false, 
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

            'choreographerState.is_public_domain' => ['boolean'],
            'choreographerState.is_adaptation' => ['boolean'],
            'choreographerState.is_attending' => ['boolean'],
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

        $this->success(
            icon: 'o-check-circle', 
            title: 'Coreógrafo(a) Adicionado(a)', 
            description:'As informações do(a) coreógrafo(a) foram adicionadas com sucesso',
            position: 'toast-top toast-center',
            css: "bg-green-500 border-green-500 text-white text-md");

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
            
            // Preenche o estado com os dados do(a) coreógrafo(a)
            $this->choreographerState = [
                'name' => $choreographer->name,
                'is_public_domain' => $choreographer->is_public_domain,
                'is_adaptation' => $choreographer->is_adaptation, 
                'is_attending' => $choreographer->is_attending, 
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

            $this->success(
                icon: 'o-check-circle', 
                title: 'Coreǵrafoa(a) Atualizado(a)', 
                description:'As informaçoes do(a) coreógrafo(a) foram atualizadas com sucesso',
                position: 'toast-top toast-center',
                css: "bg-green-500 border-green-500 text-white text-md");

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
     * Confirma e executa a remoção do(a) coreógrafo(a).
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
                $this->success(
                    icon: 'o-check-circle', 
                    title: 'Coreógrafo(a) Excluído', 
                    description:'As informações do(a) coreógrafo(a) foram excluídas com sucesso',
                    position: 'toast-top toast-center',
                    css: "bg-green-500 border-green-500 text-white text-md");

                $this->loadChoreographers();
            } catch (\Exception $e) {
                $this->error(
                    icon: 'o-information-circle', 
                    title: 'Erro', 
                    description: $e->getMessage(),
                    position: 'toast-top toast-center',
                    css: "bg-red-500 border-red-500 text-white text-md");

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