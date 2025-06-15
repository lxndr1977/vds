<?php

namespace App\Livewire\Site\Traits;

use App\Models\Choreography;
use App\Models\ChoreographyCategory;
use App\Models\ChoreographyType;
use App\Models\DanceStyle;
use Illuminate\Database\Eloquent\Collection;

trait WithChoreographyStep
{
    public Collection $choreographies;
    public Collection $choreographyTypes;
    public Collection $choreographyCategories;
    public Collection $danceStyles;

    public bool $choreographyModal = false;
    public bool $isEditingChoreography = false;
    public ?int $editingChoreographyId = null;
    public bool $deleteChoreographyModal = false;
    public int $choreographyToDelete = 0;
    
    // Modais de gerenciar participantes
    public bool $manageChoreographersModal = false;
    public bool $manageDancersModal = false;
    public string $choreographerSearch = '';
    public string $dancerSearch = '';

    // Estado para o formulário de nova coreografia
    public array $choreographyState = [
        'name' => '',
        'choreography_type_id' => '',
        'choreography_category_id' => '',
        'dance_style_id' => '',
        'music' => '',
        'music_composer' => '',
        'duration' => '',
    ];

    public ?int $selectedChoreographyId = null;
    public array $dancersForChoreography = [];
    public array $choreographersForChoreography = [];

    /**
     * Inicializa os dados da etapa de coreografias.
     *
     * @return void
     */
    public function mountChoreographyStep()
    {
        $this->choreographyTypes = ChoreographyType::all();
        $this->choreographyCategories = ChoreographyCategory::all();
        $this->danceStyles = DanceStyle::all();
        $this->loadChoreographies();
    }

    /**
     * Carrega as coreografias da escola.
     *
     * @return void
     */
    public function loadChoreographies()
    {
        $this->choreographies = $this->school->exists 
            ? $this->school->choreographies()->with('dancers', 'choreographers')->get() 
            : new Collection();
    }
    
    /**
     * Validação para uma nova coreografia.
     */
    protected function choreographyRules(): array
    {
        return [
            'choreographyState.name' => ['required', 'string', 'max:255'],
            'choreographyState.choreography_type_id' => ['required', 'exists:choreography_types,id'],
            'choreographyState.choreography_category_id' => ['required', 'exists:choreography_categories,id'],
            'choreographyState.dance_style_id' => ['required', 'exists:dance_styles,id'],
            'choreographyState.music' => ['required', 'string', 'max:255'],
            'choreographyState.music_composer' => ['required', 'string', 'max:255'],
            'choreographyState.duration' => ['required', 'string', 'max:8'], // Formato HH:MM:SS
        ];
    }
    
    /**
     * Adiciona uma nova coreografia.
     *
     * @return void
     */
    public function addChoreography() 
    {
        $this->validate($this->choreographyRules());

        $this->school->choreographies()->create($this->choreographyState);

        $this->closeChoreograpyModal();

        $this->success(title: 'Adicionado', icon: 'o-check-circle', description:'Coreografia adicionada com sucesso');
        $this->resetChoreographyForm();
        $this->choreographyModal = false; 
        $this->loadChoreographies();
    }

    /**
     * Prepara o modal para editar uma coreografia existente.
     *
     * @param int $choreographyId
     * @return void
     */
    public function editChoreography(int $choreographyId)
    {
        $choreography = $this->school->choreographies()->find($choreographyId);

        if (!$choreography) {
            $this->success(title: 'error', icon: 'o-information-circle', description:'Coreografia não encontrada');
            return;
        }

        $this->isEditingChoreography = true;
        $this->editingChoreographyId = $choreographyId;
        $this->choreographyState = [
            'name' => $choreography->name,
            'choreography_type_id' => $choreography->choreography_type_id,
            'choreography_category_id' => $choreography->choreography_category_id,
            'dance_style_id' => $choreography->dance_style_id,
            'music' => $choreography->music,
            'music_composer' => $choreography->music_composer,
            'duration' => $choreography->duration,
        ];
        $this->choreographyModal = true;
    }

    /**
     * Atualiza uma coreografia existente.
     *
     * @return void
     */
    public function updateChoreography()
    {
        $this->validate($this->choreographyRules());

        $choreography = $this->school->choreographies()->find($this->editingChoreographyId);

        if (!$choreography) {
            $this->success(title: 'error', icon: 'o-information-circle', description:'Coreografia não encontrada');
            return;
        }

        $choreography->update($this->choreographyState);

        $this->success(title: 'Atualizado', icon: 'o-check-circle', description:'Coreografia atualizada com sucesso');
        $this->choreographyModal = false;
        $this->resetChoreographyForm();
        $this->loadChoreographies();
    }

    /**
     * Reseta o formulário de coreografia e flags de edição.
     *
     * @return void
     */
    public function resetChoreographyForm()
    {
        $this->reset('choreographyState', 'isEditingChoreography', 'editingChoreographyId', 'deleteChoreographyModal', 'choreographyToDelete');
        $this->resetValidation();
    }

    /**
     * Intercepta o fechamento do modal para resetar a edição.
     */
    public function updatedChoreographyModal($value)
    {
        if (!$value && $this->isEditingChoreography) {
            $this->resetChoreographyForm();
        }
    }

    /**
     * Abre o modal de confirmação de exclusão.
     *
     * @param int $choreographyId
     * @return void
     */
    public function openDeleteChoreographyConfirm(int $choreographyId)
    {
        $this->choreographyToDelete = $choreographyId;
        $this->deleteChoreographyModal = true;
    }

    /**
     * Confirma e executa a remoção da coreografia.
     *
     * @return void
     */
    public function confirmRemoveChoreography()
    {
        $this->removeChoreography($this->choreographyToDelete);
        $this->deleteChoreographyModal = false;
        $this->choreographyToDelete = 0;
    }
    
    /**
     * Remove uma coreografia.
     *
     * @param int $choreographyId
     * @return void
     */
    public function removeChoreography(int $choreographyId)
    {
        $choreography = $this->school->choreographies()->find($choreographyId);

        if (!$choreography) {
            $this->dispatch('notify', message: 'Coreografia não encontrada.', type: 'error');
            return;
        }

        try {
            $choreography->delete();

            $this->success(title: 'Excluído', icon: 'o-check-circle', description:'Coreografia excluída com sucesso');
            $this->loadChoreographies();
        } catch (\Exception $e) {
            $this->dispatch('notify', message: $e->getMessage(), type: 'error');
        }
    }

   /**
 * Seleciona uma coreografia e abre o modal de gerenciar coreógrafos.
 *
 * @param int $choreographyId
 * @return void
 */
public function selectChoreographyForChoreographers(int $choreographyId)
{
    // Força a limpeza completa dos estados antes de definir novos valores
    $this->reset('selectedChoreographyId', 'choreographersForChoreography', 'choreographerSearch');
    
    $this->selectedChoreographyId = $choreographyId;
    
    $choreography = $this->choreographies->find($choreographyId);
    
    if ($choreography) {
        // Carrega os IDs dos coreógrafos já associados
        $this->choreographersForChoreography = $choreography->choreographers->pluck('id')->map(fn($id) => (string)$id)->toArray();
    }
    
    // Força uma atualização antes de abrir o modal
    $this->dispatch('$refresh');
    
    // Abre o modal de gerenciar coreógrafos
    $this->manageChoreographersModal = true;
}

/**
 * Seleciona uma coreografia e abre o modal de gerenciar dançarinos.
 *
 * @param int $choreographyId
 * @return void
 */
public function selectChoreographyForDancers(int $choreographyId)
{
    // Força a limpeza completa dos estados antes de definir novos valores
    $this->reset('selectedChoreographyId', 'dancersForChoreography', 'dancerSearch');
    
    $this->selectedChoreographyId = $choreographyId;
    
    $choreography = $this->choreographies->find($choreographyId);
    
    if ($choreography) {
        // Carrega os IDs dos dançarinos já associados
        $this->dancersForChoreography = $choreography->dancers->pluck('id')->map(fn($id) => (string)$id)->toArray();
    }
    
    // Força uma atualização antes de abrir o modal
    $this->dispatch('$refresh');
    
    // Abre o modal de gerenciar dançarinos
    $this->manageDancersModal = true;
}

    /**
     * Atualiza apenas os coreógrafos de uma coreografia.
     *
     * @return void
     */
    public function syncChoreographers()
    {
        if (!$this->selectedChoreographyId) return;

        $choreography = $this->choreographies->find($this->selectedChoreographyId);
        if ($choreography) {
            // Sincroniza os coreógrafos (membros)
            $choreography->choreographers()->sync($this->choreographersForChoreography);

            $this->success(title: 'Atualizado', icon: 'o-check-circle', description:'Coreógrafos atualizados com sucesso');
            $this->closeManageChoreographersModal();
            $this->loadChoreographies(); // Recarrega para exibir os contadores atualizados
        }
    }

    /**
     * Atualiza apenas os dançarinos de uma coreografia.
     *
     * @return void
     */
    public function syncDancers()
    {
        if (!$this->selectedChoreographyId) return;

        $choreography = $this->choreographies->find($this->selectedChoreographyId);
        if ($choreography) {
            // Verifica quantidade de dançarinos permitidos
            $type = $this->choreographyTypes->firstWhere('id', $choreography->choreography_type_id);
            if ($type) {
                $count = count($this->dancersForChoreography);
                if ($count < $type->min_dancers || $count > $type->max_dancers) {
                    session()->flash("error", "O número de dançarinos deve estar entre {$type->min_dancers} e {$type->max_dancers}.");
                    return;
                }
            }
            
            // Sincroniza os dançarinos
            $choreography->dancers()->sync($this->dancersForChoreography);

            $this->success(title: 'Atualizado', icon: 'o-check-circle', description:'Dançarinos atualizados com sucesso');
            $this->closeManageDancersModal();
            $this->loadChoreographies(); // Recarrega para exibir os contadores atualizados
        }
    }

    /**
     * Limpa a seleção da coreografia e fecha o modal de coreógrafos.
     */
    public function closeManageChoreographersModal()
    {
        $this->reset('selectedChoreographyId', 'choreographersForChoreography', 'choreographerSearch');
        $this->manageChoreographersModal = false;
    }

    /**
     * Limpa a seleção da coreografia e fecha o modal de dançarinos.
     */
    public function closeManageDancersModal()
    {
        $this->reset('selectedChoreographyId', 'dancersForChoreography', 'dancerSearch');
        $this->manageDancersModal = false;
    }

    /**
     * Limpa a seleção da coreografia.
     */
    public function unselectChoreography()
    {
        $this->reset('selectedChoreographyId', 'dancersForChoreography', 'choreographersForChoreography');
    }

    /**
     * Intercepta o fechamento dos modais de participantes para resetar os dados.
     */
    public function updatedManageChoreographersModal($value)
    {
        if (!$value) {
            $this->closeManageChoreographersModal();
        }
    }

    public function updatedManageDancersModal($value)
    {
        if (!$value) {
            $this->closeManageDancersModal();
        }
    }

    /**
     * Retorna os coreógrafos filtrados pela pesquisa.
     */
    public function getFilteredChoreographersProperty()
    {
        $choreographers = $this->choreographers;
        
        if (!empty($this->choreographerSearch)) {
            $choreographers = $choreographers->filter(function ($choreographer) {
                return stripos($choreographer->name, $this->choreographerSearch) !== false;
            });
        }

        return $choreographers->sortBy('name');
    }


    /**
     * Retorna os dançarinos filtrados pela pesquisa.
     */ 
    public function getFilteredDancersProperty()
    {
        $dancers = $this->dancers;
        
        if (!empty($this->dancerSearch)) {
            $dancers = $dancers->filter(function ($dancer) {
                return stripos($dancer->name, $this->dancerSearch) !== false;
            });
        }

        return $dancers->sortBy('name');
    }


    /**
     * NOTA: Este trait assume que você tem as seguintes relações de muitos-para-muitos
     * definidas nos seus Models:
     * * Em App\Models\Choreography:
     * public function dancers() { return $this->belongsToMany(Dancer::class); }
     * public function choreographers() { return $this->belongsToMany(Member::class, 'choreography_choreographer'); }
     * * E as tabelas pivot correspondentes:
     * - 'choreography_dancer' (choreography_id, dancer_id)
     * - 'choreography_choreographer' (choreography_id, member_id)
     */

    public function openChoreograpyModal()
    {
        $this->choreographyModal = true;
    }

    public function closeChoreograpyModal()
    {
        $this->choreographyModal = false;
    }
}