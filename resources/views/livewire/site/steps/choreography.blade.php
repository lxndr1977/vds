
<div class="grid grid-cols-1 md:grid-cols-2 mb-8">
    <div class="mb-6 col-span-2 md:col-span-1">
        <h2 class="text-xl md:text-2xl font-medium mb-1">Etapa 6: Coreografias</h2>
        <p class="text-sm">Cadastre todos os coreógrafos que participarão do evento.</p>
    </div>
    <div class="mb-6 col-span-2 md:col-span-1 text-start md:text-end">
        <x-mary-button icon="o-plus" wire:click="resetChoreographyForm" @click="$wire.choreographyModal = true" class="btn-primary w-auto" >
            Adicionar Coreografia
        </x-mary-button>
    </div>
</div>

{{-- Lista de coreografias cadastradas responsiva --}}
<div class="flex items-center gap-2  mb-4">
    <h3 class="text-lg font-semibold">Coreografias Cadastradas</h3>

    @if($choreographies->count() > 0)
        <x-mary-badge value="{{ $dancers->count() }}" class="badge-soft badge-sm indicator-item"/>
    @endif
</div>

@if($choreographies->count() > 0)
    <div class="space-y-3">
        @foreach($choreographies as $choreography)
            <div class="bg-white border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow">
                <div class="">
                    {{-- Informações da Coreografia --}}
                    <div class="flex items-center justify-between mb-3">
                        <div class="flex-1 min-w-0">
                            <p class="font-medium text-gray-900 line-clamp-1">{{ $choreography->name }}</p>
                            <p class="text-sm text-gray-600 line-clamp-1">
                                {{ $choreography->choreographyType->name }} | {{ $choreography->dancers->count() }} dançarino(s) | {{ $choreography->choreographers->count() }} coreógrafo(s)
                            </p>
                        </div>
                            
                        {{-- Botões de Ação --}}
                        <div class="flex gap-2 ml-4">
                            <x-mary-button 
                                icon="o-pencil" 
                                wire:click="editChoreography({{ $choreography->id }})" 
                                spinner 
                                class="btn-square btn-xs md:btn-sm btn-ghost" 
                                title="Editar coreografia"
                            />
                            <x-mary-button 
                                icon="o-trash" 
                                wire:click="openDeleteChoreographyConfirm({{ $choreography->id }})" 
                                spinner 
                                class="btn-square btn-xs md:btn-sm btn-ghost" 
                                title="Remover coreografia"
                            />
                        </div>
                    </div>
                    
                    {{-- Links de Gerenciamento --}}
                    <div class="mb-3">
                        @if($choreography->dancers->count() == 0)
                            <span class="w-auto text-red-500 text-sm font-medium">
                                Adicione bailarinos(as) nesta coreografia
                            </span>
                        @endif
                    </div>

                    <div class="flex gap-4 text-sm">
                        <x-mary-button 
                            icon="o-users" 
                            wire:click="selectChoreographyForChoreographers({{ $choreography->id }})"
                            class="font-medium btn-primary btn-soft "
                        >
                            Coreógrafos
                        </x-mary-button>
                        <x-mary-button 
                            icon="o-users" 
                            wire:click="selectChoreographyForDancers({{ $choreography->id }})"
                            class="font-medium btn-primary btn-soft"
                        >
                            Bailarino(as)
                        </x-mary-button>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@else
    <div class="bg-gray-50 border border-gray-200 rounded-lg p-8 text-center">
        <div class="text-gray-400 mb-2">
            <svg class="w-12 h-12 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3"></path>
            </svg>
        </div>
        <p class="text-gray-500">Nenhuma coreografia cadastrada ainda</p>
        <p class="text-sm text-gray-400 mt-1 mb-6">Clique em "Adicionar Coreografia" para começar</p>
        <x-mary-button 
            icon="o-plus" 
            wire:click="resetChoreographyForm" 
            @click="$wire.choreographyModal = true" 
            class="btn-primary w-auto" >
                Adicionar Coreografia
        </x-mary-button>
    </div>
@endif


<x-mary-modal 
        wire:model="choreographyModal" 
        title="{{ $isEditingChoreography ? 'Editar Coreografia' : 'Cadastrar Coreografia' }}" 
        class="backdrop-blur modal-box-lg"
    >
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="col-span-3">
                 <div 
                    x-data 
                    @input="$wire.clearError('choreographyState.name')"
                >
                    <x-mary-input 
                        label="Nome da Coreografia" 
                        wire:model.lazy="choreographyState.name" 
                        id="name"
                        placeholder="Nome da Coreografia"  
                        error-class="font-bold text-red-600"
                    />
                </div>
            </div>
            
            {{-- Projetos Sociais/Universitários --}}
            <div class="md:col-span-3 flex flex-col md:flex-row items-center space-y-6 md:space-y-0 md:space-x-8">
                <div 
                    x-data 
                    @input="$wire.clearError('choreographyState.is_social_project')"
                >
                    <x-mary-toggle 
                        label="É um projeto social?" 
                        wire:model.defer="choreographyState.is_social_project" 
                        id="is_social_project" 
                    />
                </div>

                <div 
                    x-data 
                    @input="$wire.clearError('choreographyState.is_university_project')"
                >
                    <x-mary-toggle 
                        label="É um projeto universitário?" 
                        wire:model.defer="choreographyState.is_university_project" 
                        id="is_university_project" 
                    />
                </div>
            </div>

            <div>
                 <div 
                    x-data 
                    @input="$wire.clearError('choreographyState.choreography_type_id')"
                >
                    <x-mary-select
                        label="Formação"
                        wire:model.lazy="choreographyState.choreography_type_id"
                        id="choreography_type_id"
                        :options="$choreographyTypes"
                        placeholder="Selecione"
                        placeholder-value="0" 
                        error-class="font-bold text-red-600"
                    />
                 </div>
            </div>

            <div>
                 <div 
                    x-data 
                    @input="$wire.clearError('choreographyState.choreography_category_id')"
                >
                    <x-mary-select
                        label="Categoria"
                        wire:model.lazy="choreographyState.choreography_category_id"
                        id="choreography_category_id"
                        :options="$choreographyCategories"
                        placeholder="Selecione"
                        placeholder-value="0" 
                        error-class="font-bold text-red-600"
                    />
                 </div>
            </div>

            <div>
                 <div 
                    x-data 
                    @input="$wire.clearError('choreographyState.dance_style_id')"
                >
                    <x-mary-select
                        label="Modalidade"
                        wire:model.lazy="choreographyState.dance_style_id"
                        id="dance_style_id"
                        :options="$danceStyles"
                        placeholder="Selecione"
                        placeholder-value="0" 
                        error-class="font-bold text-red-600"
                    />
                 </div>
            </div>

            <div>
                 <div 
                    x-data 
                    @input="$wire.clearError('choreographyState.music')"
                >
                    <x-mary-input 
                        label="Música" 
                        wire:model.lazy="choreographyState.music" 
                        id="music"
                        placeholder="Música"  
                        error-class="font-bold text-red-600"
                    />
                 </div>
            </div>

            <div>
                 <div 
                    x-data 
                    @input="$wire.clearError('choreographyState.music_composer')"
                >
                    <x-mary-input 
                        label="Compositor" 
                        wire:model.lazy="choreographyState.music_composer" 
                        id="music_composer"
                        placeholder="Compositor"  
                        error-class="font-bold text-red-600"
                    />
                 </div>
            </div>

            <div>
                <div 
                    x-data="{ 
                        formatDuration(value) {
                            if (!value) return '';
                            let v = value.replace(/\D/g, '').substring(0, 4);
                            if (v.length >= 3) {
                                v = v.replace(/(\d{2})(\d{2})/, '$1:$2');
                            } else if (v.length >= 2) {
                                v = v.replace(/(\d{2})/, '$1:');
                            }
                            return v;
                        }
                    }"
                    x-init="
                        $nextTick(() => {
                            let input = $el.querySelector('input');
                            if (input && input.value) {
                                input.value = formatDuration(input.value);
                            }
                        })
                    "
                    @input="$wire.clearError('choreographyState.duration')"
                >
                    <x-mary-input 
                        label="Duração" 
                        wire:model.lazy="choreographyState.duration" 
                        id="duration"
                        placeholder="MM:SS"  
                        error-class="font-bold text-red-600"
                        x-on:input="
                            let v = formatDuration($event.target.value);
                            $event.target.value = v;
                            $wire.set('choreographyState.duration', v);
                        "
                        maxlength="5"
                    />
                </div>
            </div>
        </div>

        <x-slot:actions>
            <x-mary-button @click="$wire.choreographyModal = false">
                Cancelar
            </x-mary-button>

            <x-mary-button 
                icon="o-check" 
                wire:click="{{ $isEditingChoreography ? 'updateChoreography' : 'addChoreography' }}"
                class="btn-primary" 
                spinner="{{ $isEditingChoreography ? 'updateChoreography' : 'addChoreography' }}"
            >
                {{ $isEditingChoreography ? 'Atualizar' : 'Adicionar' }}
            </x-mary-button>
        </x-slot:actions>
    </x-mary-modal>

{{-- Modal de Confirmação de Exclusão --}}
<x-mary-modal 
    wire:model="deleteChoreographyModal" title="Confirmar Exclusão" class="backdrop-blur"
>
    <div class="py-4">
        <div class="flex items-center gap-3 mb-4">
            <div class="flex-shrink-0">
                <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.994-.833-2.464 0L3.349 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                </svg>
            </div>
            <div>
                <p class="text-gray-900 font-medium">Tem certeza que deseja remover esta coreografia?</p>
                <p class="text-gray-600 text-sm mt-1">Esta ação não pode ser desfeita.</p>
            </div>
        </div>
    </div>
    <x-slot:actions>
        <x-mary-button @click="$wire.deleteChoreographyModal = false">
            Cancelar
        </x-mary-button>

        <x-mary-button 
            icon="o-trash" 
            wire:click="confirmRemoveChoreography" 
            class="btn-error" 
            spinner="confirmRemoveChoreography"
        >
            Excluir
        </x-mary-button>
    </x-slot:actions>
</x-mary-modal>

{{-- Modal para Gerenciar Coreógrafos --}}
<x-mary-modal 
    wire:model="manageChoreographersModal" 
    title="Gerenciar Coreógrafos" 
    class="backdrop-blur"
    box-class="max-w-2xl w-full mx-4"
>
    @if($selectedChoreographyId)
        <div class="mb-4">
            <h4 class="text-lg font-semibold">{{ $choreographies->find($selectedChoreographyId)?->name }}</h4>
        </div>

        <div>
            <h4 class="font-semibold mb-3">Selecionar Coreógrafos</h4>
            
            {{-- Campo de pesquisa para coreógrafos --}}
            <div class="mb-3">
                <x-mary-input 
                    wire:model.live="choreographerSearch"
                    placeholder="Pesquisar coreógrafo..."
                    icon="o-magnifying-glass"
                    clearable
                />
            </div>

            <div class="h-80 overflow-y-auto border rounded-md p-3 space-y-2 bg-gray-50">
                @forelse($this->filteredChoreographers as $choreographer)
                    <label class="flex items-center p-2 hover:bg-white rounded cursor-pointer" 
                    wire:key="choreographer-{{ $choreographer->id }}"
                    >
                        <input 
                            type="checkbox" 
                            wire:model="choreographersForChoreography" 
                            value="{{ $choreographer->id }}" 
                            class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50"
                        >
                        <span class="ml-3 text-sm">{{ $choreographer->name }}</span>
                    </label>
                @empty
                    <p class="text-gray-500 text-sm text-center py-4">
                        @if($choreographerSearch)
                            Nenhum coreógrafo encontrado para "{{ $choreographerSearch }}"
                        @else
                            Nenhum coreógrafo disponível
                        @endif
                    </p>
                @endforelse
            </div>
        </div>
    @endif

    <x-slot:actions>
        <x-mary-button @click="$wire.manageChoreographersModal = false">
            Cancelar
        </x-mary-button>

        <x-mary-button 
            icon="o-check" 
            wire:click="syncChoreographers" 
            class="btn-primary" 
            spinner="syncChoreographers"
        >
            Salvar Coreógrafos
        </x-mary-button>
    </x-slot:actions>
</x-mary-modal>

{{-- Modal para Gerenciar Bailarino(as) --}}
<x-mary-modal 
    wire:model="manageDancersModal" 
    title="Gerenciar Bailarino(as)" 
    class="backdrop-blur"
    box-class="max-w-2xl w-full mx-4"
>
    @if($selectedChoreographyId)
        <div class="mb-4">
            <h4 class="text-lg font-semibold">{{ $choreographies->find($selectedChoreographyId)?->name }}</h4>
        </div>

        @if(session('error'))
            <x-mary-alert 
                title="{{ session('error') }}" 
                icon="o-exclamation-triangle" 
                class="mb-2 bg-red-500 text-white"/>
        @endif

        <div>
            <h4 class="font-semibold mb-3">Selecionar Bailarino(as)</h4>
            
            {{-- Campo de pesquisa para dançarinos --}}
            <div class="mb-3">
                <x-mary-input 
                    wire:model.live="dancerSearch"
                    placeholder="Pesquisar dançarino..."
                    icon="o-magnifying-glass"
                    clearable
                />
            </div>

            <div class="h-80 overflow-y-auto border rounded-md p-3 space-y-2 bg-gray-50">
                @forelse($this->filteredDancers as $dancer)
                    <label class="flex items-center p-2 hover:bg-white rounded cursor-pointer"
                    wire:key="dancer-{{ $dancer->id }}"
                    >
                        <input 
                            type="checkbox" 
                            wire:model="dancersForChoreography" 
                            value="{{ $dancer->id }}" 
                            class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50"
                        >
                        <span class="ml-3 text-sm">{{ $dancer->name }}</span>
                    </label>
                @empty
                    <p class="text-gray-500 text-sm text-center py-4">
                        @if($dancerSearch)
                            Nenhum dançarino encontrado para "{{ $dancerSearch }}"
                        @else
                            Nenhum dançarino disponível
                        @endif
                    </p>
                @endforelse
            </div>
        </div>
    @endif

    <x-slot:actions>
        <x-mary-button @click="$wire.manageDancersModal = false">
            Cancelar
        </x-mary-button>

        <x-mary-button 
            icon="o-check" 
            wire:click="syncDancers" 
            class="btn-primary" 
            spinner="syncDancers"
        >
            Salvar Bailarino(as)
        </x-mary-button>
    </x-slot:actions>
</x-mary-modal>