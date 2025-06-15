{{-- Etapa 2: Membros da Equipe --}}
<div>
    <div class="flex flex-col md:flex-row  items-start md:items-center justify-start md:justify-between mb-8">
        <div class="mb-6">
            <h2 class="text-xl md:text-2xl font-medium mb-1">Etapa 2: Membros</h2>
            <p class="text-sm">Cadastre 1 diretor e/ou até 3 coordenadores da escola, grupo ou companhia.</p>
        </div>

         <div class="text-start md:text-right mt-4 mb-8">
            <x-mary-button 
                icon="o-plus" 
                @click="$wire.memberModal = true" 
                class="btn-primary" 
            >
                Adicionar Membro
            </x-mary-button>
        </div>
    </div>


    {{-- Lista de membros cadastrados responsiva --}}
    <h3 class="text-lg font-semibold mb-4">Membros Cadastrados</h3>
    
    @if($members->count() > 0)
        <div class="space-y-3">
            @foreach($members as $member)
                <div class="bg-white border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow">
                    <div class="flex items-center justify-between">
                        {{-- Informações do Membro --}}
                        <div class="flex-1 min-w-0">
                            <p class="font-medium text-gray-900 line-clamp-1">{{ $member->name }}</p>
                            <p class="text-sm text-gray-600 line-clamp-1">{{ $member->memberType->name ?? 'N/A' }}</p>
                        </div>
                        
                        {{-- Botões de Ação --}}
                        <div class="flex gap-2 ml-4">
                            <x-mary-button 
                                icon="o-pencil" 
                                wire:click="editMember({{ $member->id }})" 
                                spinner 
                                class="btn-square btn-xs md:btn-sm btn-ghost" 
                                title="Editar membro"
                                tooltip="Editar"
                            />
                            <x-mary-button 
                                icon="o-trash" 
                                wire:click="prepareDeleteMember({{ $member->id }})" 
                                spinner 
                                class="btn-square btn-xs md:btn-sm btn-ghost" 
                                title="Remover membro"
                                tooltip="Excluir"
                            />
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="bg-gray-50 border border-gray-200 rounded-lg p-8 text-center">
            <div class="text-gray-400 mb-2">
                <svg class="w-12 h-12 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                </svg>
            </div>
            <p class="text-gray-500">Nenhum membro cadastrado ainda</p>
            <p class="text-sm text-gray-400 mt-1">Clique em "Adicionar Membro" para começar</p>
        </div>
    @endif
</div>


 <x-mary-modal 
            wire:model="memberModal" 
            :title="$isEditing ? 'Editar membro' : 'Cadastrar membro'" 
            class="backdrop-blur w-auto overflow-none"
            @close="$wire.closeMemberModal()"
        >
            <div class="grid grid-cols-1 gap-4 max-w-7xl min-w  -[1220px] overflow-none">
                <div 
                    x-data 
                    @input="$wire.clearError('memberState.name')"
                >
                    <x-mary-input 
                        label="Nome" 
                        wire:model.lazy="memberState.name" 
                        id="member_name"
                        placeholder="Nome"  
                        error-class="font-bold text-red-600"
                    />
                </div>

                <div 
                    x-data 
                    @input="$wire.clearError('memberState.email')"
                >
                    <x-mary-input 
                        label="Email" 
                        wire:model.lazy="memberState.email" 
                        id="member_email"
                        placeholder="Email"  
                        error-class="font-bold text-red-600"
                    />
                </div>

                <div 
                    x-data 
                    @input="$wire.clearError('memberState.member_type_id')"
                >
                    <x-mary-select
                        label="Função"
                        wire:model.lazy="memberState.member_type_id"
                        id="member_type"
                        :options="$memberTypes"
                        placeholder="Selecione"
                        placeholder-value="0" 
                        error-class="font-bold text-red-600"
                    />
                </div>
            </div>

            <x-slot:actions>
                <x-mary-button 
                    icon="o-x-mark" 
                    @click="$wire.closeMemberModal()" 
                >
                    Cancelar
                </x-mary-button>

                @if($isEditing)
                    <x-mary-button 
                        icon="o-check" 
                        wire:click="updateMember"  
                        class="btn-primary" 
                        spinner="updateMember"
                    >
                        Atualizar
                    </x-mary-button>
                @else
                    <x-mary-button 
                        icon="o-check" 
                        wire:click="addMember"  
                        class="btn-primary" 
                        spinner="addMember"
                    >
                        Adicionar
                    </x-mary-button>
                @endif
            </x-slot:actions>
        </x-mary-modal>


        {{-- Modal de Confirmação de Exclusão --}}
        <x-mary-modal wire:model="confirmDeleteModal" title="Confirmar exclusão" class="backdrop-blur">
            <div class="py-4">
                <div class="flex items-center gap-3 mb-4">
                    <div class="flex-shrink-0">
                        <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.994-.833-2.464 0L3.349 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="text-gray-900 font-medium">Tem certeza que deseja remover este membro?</p>
                        <p class="text-gray-600 text-sm mt-1">Esta ação não pode ser desfeita.</p>
                    </div>
                </div>
            </div>

            <x-slot:actions>
                <x-mary-button 
                    icon="o-x-mark" 
                    @click="$wire.confirmDeleteModal = false" 
                >
                    Cancelar
                </x-mary-button>

                <x-mary-button 
                    icon="o-trash" 
                    wire:click="confirmRemoveMember"  
                    class="btn-error" 
                    spinner="confirmRemoveMember"
                >
                    Remover
                </x-mary-button>
            </x-slot:actions>
        </x-mary-modal>