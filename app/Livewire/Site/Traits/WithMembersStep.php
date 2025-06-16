<?php

namespace App\Livewire\Site\Traits;

use App\Models\Member;
use Mary\Traits\Toast;
use App\Models\MemberType;
use Illuminate\Support\Facades\Cache;
use Illuminate\Database\Eloquent\Collection;

trait WithMembersStep
{
    use Toast;

    public Collection $members;
    public Collection $memberTypes;

    public bool $memberModal = false;
    public bool $confirmDeleteModal = false;

    // Estado para o formulário de adição de novo integrante
    public array $memberState = [
        'name' => '',
        'member_type_id' => '',
    ];

    // Estados para edição
    public bool $isEditing = false;
    public ?int $editingMemberId = null;
    public ?int $memberToDelete = null;

    /**
     * Inicializa os dados da etapa de membros.
     *
     * @return void
     */
    public function mountMembersStep()
    {
        $this->memberTypes = Cache::remember('member_types', 604800, function () {
            return MemberType::all();
            
        });
        $this->loadMembers();
    }

    /**
     * Carrega os membros associados à escola.
     *
     * @return void
     */
    public function loadMembers()
    {
        $this->members = $this->school->exists ? $this->school->members()->get() : new Collection();
    }

    /**
     * Validação para um novo integrante.
     *
     * @return array
     */
    protected function memberRules(): array
    {
        return [
            'memberState.name' => ['required', 'string', 'max:255'],
            'memberState.member_type_id' => ['required', 'exists:member_types,id'],
        ];
    }

    protected function memberMessages(): array
    {
        return [
            'memberState.name.required' => 'O nome é obrigatório.',
            'memberState.name.string' => 'O nome deve ser um texto.',
            'memberState.name.max' => 'O nome não pode ter mais de 255 caracteres.',

            'memberState.member_type_id.required' => 'a função do integrante é obrigatório.',
            'memberState.member_type_id.exists' => 'a função do integrante selecionado é inválido.',
        ];
    }

    /**
     * Adiciona um novo integrante à escola.
     *
     * @return void
     */
    public function addMember()
    {
        $this->validate($this->memberRules(), $this->memberMessages());

        // Recupera o tipo de integrante selecionado
        $memberType = MemberType::find($this->memberState['member_type_id']);

        // Conta quantos membros já existem com esse tipo para a escola atual
        $currentCount = $this->school->members()
            ->where('member_type_id', $memberType->id)
            ->count();

        // Verifica se atingiu o limite máximo
        if ($memberType->max_limit !== null && $currentCount >= $memberType->max_limit) {
            $this->closeMemberModal();

            $this->error(
                title: 'Atenção ao limite de membros', 
                icon: 'o-information-circle', 
                description:"Limite máximo de {$memberType->max_limit} integrante(s) para o tipo '{$memberType->name}' atingido.",
                position: 'toast-top toast-center',
                css: "bg-red-500 border-red-500 text-white text-md");
            
            return;
        }
        
        $this->school->members()->create($this->memberState);

        $this->closeMemberModal();
        
        $this->success(
            title: 'Integrante Adicionado', 
            icon: 'o-check-circle', 
            description:'As informações do integrante foram adicionadas com sucesso',
            position: 'toast-top toast-center',
            css: "bg-green-500 border-green-500 text-white text-md");

        $this->reset('memberState'); 
        $this->resetValidation();        
        $this->loadMembers(); 
    }

    /**
     * Abre o modal para editar um integrante existente.
     *
     * @param int $memberId
     * @return void
     */
    public function editMember(int $memberId)
    {
        $member = $this->school->members()->find($memberId);
        
        if ($member) {
            $this->isEditing = true;
            $this->editingMemberId = $memberId;
            
            // Preenche o estado com os dados do integrante
            $this->memberState = [
                'name' => $member->name,
                'member_type_id' => $member->member_type_id,
            ];
            
            $this->openMemberModal();
        }
    }

    /**
     * Atualiza um integrante existente.
     *
     * @return void
     */
    public function updateMember()
    {
        $this->validate($this->memberRules(), $this->memberMessages());

        $member = $this->school->members()->find($this->editingMemberId);
        
        if ($member) {
            // Recupera o tipo de integrante selecionado
            $memberType = MemberType::find($this->memberState['member_type_id']);

            // Se o tipo foi alterado, verifica o limite
            if ($member->member_type_id != $this->memberState['member_type_id']) {
                $currentCount = $this->school->members()
                    ->where('member_type_id', $memberType->id)
                    ->where('id', '!=', $member->id) // Exclui o integrante atual da contagem
                    ->count();

                if ($memberType->max_limit !== null && $currentCount >= $memberType->max_limit) {
                    $this->closeMemberModal();

                    $this->error(
                        title: 'Atenção ao limite de membros', 
                        icon: 'o-information-circle', 
                        description:"Limite máximo de {$memberType->max_limit} integrante(s) para o tipo '{$memberType->name}' atingido.",
                        position: 'toast-top toast-center',
                        css: "bg-red-500 border-red-500 text-white text-md");

                    return;
                }
            }

            $member->update($this->memberState);
            
             $this->success(
                title: 'Integrante Atualizado', 
                icon: 'o-check-circle', 
                description:'As informações do integrante foram atualizadas com sucesso',
                position: 'toast-top toast-center',
                css: "bg-green-500 border-green-500 text-white text-md");
                
            $this->closeMemberModal();
           
            $this->loadMembers();
        }
    }

    /**
     * Prepara a exclusão de um integrante (abre o modal de confirmação).
     *
     * @param int $memberId
     * @return void
     */
    public function prepareDeleteMember(int $memberId)
    {
        $this->memberToDelete = $memberId;
        $this->confirmDeleteModal = true;
    }
 
    /**
     * Confirma e executa a remoção do integrante.
     *
     * @return void
     */
    public function confirmRemoveMember()
    {
        if ($this->memberToDelete) {
            $this->removeMember($this->memberToDelete);
            $this->confirmDeleteModal = false;
            $this->memberToDelete = null;
        }
    }

    /**
     * Remove um integrante.
     *
     * @param int $memberId
     * @return void
     */
    public function removeMember(int $memberId)
    {
        // Garante que o integrante pertence à escola do usuário antes de deletar
        $member = $this->school->members()->find($memberId);
        if ($member) {
            $member->delete();
             $this->success(
                title: 'Integrante Excluído', 
                icon: 'o-check-circle', 
                description:'As informações do integrante foram excluídas com sucesso',
                position: 'toast-top toast-center',
                css: "bg-green-500 border-green-500 text-white text-md");   
            $this->loadMembers();
        }
    }

    public function openMemberModal()
    {
        $this->resetValidation();
        return $this->memberModal = true;
    }

    public function closeMemberModal()
    {
        $this->memberModal = false;
        $this->isEditing = false;
        $this->editingMemberId = null;
        $this->reset('memberState');
        $this->resetValidation();   
    }

}