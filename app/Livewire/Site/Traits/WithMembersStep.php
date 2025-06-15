<?php

namespace App\Livewire\Site\Traits;

use App\Models\Member;
use App\Models\MemberType;
use Illuminate\Database\Eloquent\Collection;
use Mary\Traits\Toast;

trait WithMembersStep
{
    use Toast;

    public Collection $members;
    public Collection $memberTypes;

    public bool $memberModal = false;
    public bool $confirmDeleteModal = false;

    // Estado para o formulário de adição de novo membro
    public array $memberState = [
        'name' => '',
        'phone' => '',
        'email' => '',
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
        $this->memberTypes = MemberType::all();
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
     * Validação para um novo membro.
     *
     * @return array
     */
    protected function memberRules(): array
    {
        return [
            'memberState.name' => ['required', 'string', 'max:255'],
            'memberState.phone' => ['nullable', 'string', 'max:20'],
            'memberState.email' => ['nullable', 'email', 'max:255'],
            'memberState.member_type_id' => ['required', 'exists:member_types,id'],
        ];
    }

    protected function memberMessages(): array
    {
        return [
            'memberState.name.required' => 'O nome é obrigatório.',
            'memberState.name.string' => 'O nome deve ser um texto.',
            'memberState.name.max' => 'O nome não pode ter mais de 255 caracteres.',

            'memberState.phone.string' => 'O telefone deve ser um texto.',
            'memberState.phone.max' => 'O telefone não pode ter mais de 20 caracteres.',

            'memberState.email.email' => 'Informe um e-mail válido.',
            'memberState.email.max' => 'O e-mail não pode ter mais de 255 caracteres.',

            'memberState.member_type_id.required' => 'O tipo de membro é obrigatório.',
            'memberState.member_type_id.exists' => 'O tipo de membro selecionado é inválido.',
        ];
    }

    /**
     * Adiciona um novo membro à escola.
     *
     * @return void
     */
    public function addMember()
    {
        $this->validate($this->memberRules(), $this->memberMessages());

        // Recupera o tipo de membro selecionado
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
                description:"Limite máximo de {$memberType->max_limit} membro(s) para o tipo '{$memberType->name}' atingido.");
            
            return;
        }
        
        $this->school->members()->create($this->memberState);

        $this->closeMemberModal();
        
        $this->success(title: 'Adicionado', icon: 'o-check-circle', description:'Membro adicionado com sucesso');
        $this->reset('memberState'); // Limpa o formulário de adição
        $this->resetValidation();        $this->loadMembers(); // Recarrega a lista de membros
    }

    /**
     * Abre o modal para editar um membro existente.
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
            
            // Preenche o estado com os dados do membro
            $this->memberState = [
                'name' => $member->name,
                'phone' => $member->phone ?? '',
                'email' => $member->email ?? '',
                'member_type_id' => $member->member_type_id,
            ];
            
            $this->openMemberModal();
        }
    }

    /**
     * Atualiza um membro existente.
     *
     * @return void
     */
    public function updateMember()
    {
        $this->validate($this->memberRules(), $this->memberMessages());

        $member = $this->school->members()->find($this->editingMemberId);
        
        if ($member) {
            // Recupera o tipo de membro selecionado
            $memberType = MemberType::find($this->memberState['member_type_id']);

            // Se o tipo foi alterado, verifica o limite
            if ($member->member_type_id != $this->memberState['member_type_id']) {
                $currentCount = $this->school->members()
                    ->where('member_type_id', $memberType->id)
                    ->where('id', '!=', $member->id) // Exclui o membro atual da contagem
                    ->count();

                if ($memberType->max_limit !== null && $currentCount >= $memberType->max_limit) {
                    $this->closeMemberModal();

                    $this->error(
                        title: 'Atenção ao limite de membros', 
                        icon: 'o-information-circle', 
                        description:"Limite máximo de {$memberType->max_limit} membro(s) para o tipo '{$memberType->name}' atingido.");

                    return;
                }
            }

            $member->update($this->memberState);
            
            $this->closeMemberModal();
            $this->success(title: 'Atualizado', icon: 'o-check-circle', description:'Membro atualizado com sucesso');
            $this->loadMembers();
        }
    }

    /**
     * Prepara a exclusão de um membro (abre o modal de confirmação).
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
     * Confirma e executa a remoção do membro.
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
     * Remove um membro.
     *
     * @param int $memberId
     * @return void
     */
    public function removeMember(int $memberId)
    {
        // Garante que o membro pertence à escola do usuário antes de deletar
        $member = $this->school->members()->find($memberId);
        if ($member) {
            $member->delete();
             $this->success(title: 'Excluído', icon: 'o-check-circle', description:'Membro excluído com sucesso');
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