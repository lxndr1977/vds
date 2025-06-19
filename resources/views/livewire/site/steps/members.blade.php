{{-- Etapa 2: Membros da Equipe Diretiva --}}
<div>
    <div class="flex flex-col md:flex-row items-start md:items-center justify-start md:justify-between mb-8">
        <div class="mb-6">
            <h2 class="text-xl md:text-2xl font-medium mb-1">Etapa 2: Equipe Diretiva</h2>
            <p class="text-zinc-700">Cadastre 1 Diretor e/ou até 3 Coordenadores do Grupo/Escola/Cia</p>
        </div>

         <div class="text-start md:text-right mt-4 mb-8">
            <x-mary-button 
                icon="o-plus" 
                @click="$wire.memberModal = true" 
                class="btn-primary" 
            >
                Adicionar Integrante
            </x-mary-button>
        </div>
    </div>

    {{-- Lista de membros cadastrados responsiva --}}
    <div class="flex items-center gap-2  mb-4">
        <h3 class="text-lg font-semibold">Diretores e/ou Coordenadores Cadastrados</h3>

        @if($members->count() > 0)
            <x-mary-badge value="{{ $dancers->count() }}" class="badge-soft badge-sm indicator-item"/>
        @endif
    </div>
    
    @if($members->count() > 0)
        <div class="space-y-3">
            @foreach($members as $member)
                <div class="bg-white border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow">
                    <div class="flex items-center justify-between">
                        {{-- Informações do Integrante --}}
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
                                title="Editar Integrante"
                                tooltip="Editar"
                            />
                            <x-mary-button 
                                icon="o-trash" 
                                wire:click="prepareDeleteMember({{ $member->id }})" 
                                spinner 
                                class="btn-square btn-xs md:btn-sm btn-ghost" 
                                title="Remover Integrante"
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
            <p class="text-gray-500">Nenhum Integrante cadastrado ainda</p>
            <p class="text-sm text-gray-400 mt-1 mb-6">Clique em "Adicionar Integrante" para começar</p>
           
            <x-mary-button 
                icon="o-plus" 
                @click="$wire.memberModal = true" 
                class="btn-primary" 
            >
                Adicionar Integrante
            </x-mary-button>
        </div>
    @endif
</div>

@include('livewire.site.steps.modals.member.add-or-edit')

@include('livewire.site.steps.modals.member.delete')
