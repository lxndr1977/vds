{{-- Etapa 3: Bailarinos(as) --}}
<div>
   <div class="grid grid-cols-1 md:grid-cols-2 mb-2 md:mb-8">
        <div class="mb-6">
            <h2 class="text-xl md:text-2xl font-medium mb-1">Etapa 4: Bailarinos(as)</h2>
            <p class="text-zinc-700">Cadastre todos(as) os(as) Bailarino(as) que participarão do evento.</p>
        </div>

         <div class="text-start md:text-right mb-6">
            <x-mary-button 
                icon="o-plus" 
                @click="$wire.dancerModal = true" 
                class="btn-primary" 
            >
                Adicionar Bailarino(a)
            </x-mary-button>
        </div>
    </div>
    
    {{-- Lista de Bailarino(a)s cadastrados responsiva --}}
    <div class="flex items-center gap-2  mb-4">
        <h3 class="text-lg font-semibold">Bailarinos(as) Cadastrados</h3>

        @if($dancers->count() > 0)
            <x-mary-badge value="{{ $dancers->count() }}" class="badge-soft badge-sm indicator-item"/>
        @endif
    </div>
    
    @if($dancers->count() > 0)
        <div class="space-y-3">
            @foreach($dancers as $dancer)
                <div class="bg-white border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow">
                    <div class="flex items-center justify-between">
                        {{-- Informações do Dançarino --}}
                        <div class="flex-1 min-w-0">
                            <p class="font-medium text-gray-900 line-clamp-1">{{ $dancer->name }}</p>
                            <p class="text-sm text-gray-600 line-clamp-1">
                                @if($dancer->birth_date)
                                    {{ $dancer->birth_date }}
                                @endif
                            </p>
                        </div>
                        
                        {{-- Botões de Ação --}}
                        <div class="flex gap-2 ml-4">
                            <x-mary-button 
                                icon="o-pencil" 
                                wire:click="editDancer({{ $dancer->id }})" 
                                spinner 
                                class="btn-square btn-xs md:btn-sm btn-ghost" 
                                title="Editar Bailarino(a)"
                            />
                            <x-mary-button 
                                icon="o-trash" 
                                wire:click="openDeleteConfirm({{ $dancer->id }})" 
                                spinner 
                                class="btn-square btn-xs md:btn-sm btn-ghost" 
                                title="Remover Bailarino(a)"
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
            <p class="text-gray-500">Nenhum Bailarino(a) cadastrado ainda</p>
            <p class="text-sm text-gray-400 mt-1 mb-6">Clique em "Adicionar Bailarino(a)" para começar</p>
            <x-mary-button 
                icon="o-plus" 
                @click="$wire.dancerModal = true" 
                class="btn-primary" 
            >
                Adicionar Bailarino(a)
            </x-mary-button>
        </div>
    @endif

</div>

@include('livewire.site.steps.modals.dancer.add-or-edit')
@include('livewire.site.steps.modals.dancer.delete')
