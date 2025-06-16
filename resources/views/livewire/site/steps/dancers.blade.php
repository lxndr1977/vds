{{-- Etapa 3: Bailarinos(as) --}}
<div>

    <div class="flex flex-col md:flex-row  items-start md:items-center justify-start md:justify-between mb-8">
        <div class="mb-6">
            <h2 class="text-xl md:text-2xl font-medium mb-1">Etapa 4: Bailarinos(as)</h2>
            <p class="text-zinc-700">Cadastre todos(as) os(as) Bailarino(as) que participarão do evento.</p>
        </div>

         <div class="text-start md:text-right mt-4 mb-8">
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


{{-- Modal de Adicionar/Editar Dançarino --}}
<x-mary-modal 
    wire:model="dancerModal" 
    title="{{ $isEditingDancer ? 'Editar Bailarino(a)' : 'Cadastrar Bailarino(a)' }}" 
    class="backdrop-blur"
    @close="$wire.closeDancerModal()"

>
    <div class="grid grid-cols-1 gap-4">
        <div 
            x-data 
            @input="$wire.clearError('dancerState.name')"
        >
            <x-mary-input 
                label="Nome" 
                wire:model.lazy="dancerState.name" 
                id="dancer_name"
                placeholder="Nome"  
                error-class="font-bold text-red-600"
            />
        </div>

        <div 
            x-data="{
                formatDate(input) {
                    let value = input.replace(/\D/g, '');
                    
                    if (value.length > 8) {
                        value = value.substring(0, 8);
                    }
                    
                    if (value.length >= 5) {
                        return value.replace(/(\d{2})(\d{2})(\d{4})/, '$1/$2/$3');
                    } else if (value.length >= 3) {
                        return value.replace(/(\d{2})(\d{2})/, '$1/$2');
                    } else if (value.length >= 1) {
                        return value.replace(/(\d{2})/, '$1');
                    }
                    
                    return value;
                },
                
                validateDate(value) {
                    const cleanValue = value.replace(/\D/g, '');
                    
                    if (cleanValue.length === 8) {
                        const day = parseInt(cleanValue.substring(0, 2));
                        const month = parseInt(cleanValue.substring(2, 4));
                        const year = parseInt(cleanValue.substring(4, 8));
                        
                        if (month < 1 || month > 12) return false;
                        if (day < 1 || day > 31) return false;
                        if (year < 1900 || year > new Date().getFullYear()) return false;
                        
                        const daysInMonth = [31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31];
                        
                        if (month === 2 && ((year % 4 === 0 && year % 100 !== 0) || (year % 400 === 0))) {
                            daysInMonth[1] = 29;
                        }
                        
                        return day <= daysInMonth[month - 1];
                    }
                    
                    return cleanValue.length < 8;
                }
            }" 
            @input="$wire.clearError('dancerState.birth_date')"
        >
            <x-mary-input 
                label="Data de Nascimento" 
                id="dancer_birth_date"
                wire:model.lazy="dancerState.birth_date"
                error-class="font-bold text-red-600"
                placeholder="dd/mm/aaaa"
                maxlength="10"
                x-on:input="
                    const formatted = formatDate($event.target.value);
                    $event.target.value = formatted;
                    $wire.set('dancerState.birth_date', formatted);
                    
                    if (!validateDate(formatted) && formatted.length > 0) {
                        $event.target.classList.add('border-red-500');
                    } else {
                        $event.target.classList.remove('border-red-500');
                    }
                "
                x-on:keydown="
                    if (!['Backspace', 'Delete', 'Tab', 'ArrowLeft', 'ArrowRight'].includes($event.key) && 
                        !/\d/.test($event.key)) {
                        $event.preventDefault();
                    }
                "
                x-on:paste="
                    $event.preventDefault();
                    const paste = ($event.clipboardData || window.clipboardData).getData('text');
                    const formatted = formatDate(paste);
                    $event.target.value = formatted;
                    $wire.set('dancerState.birth_date', formatted);
                "
            />
        </div>
    </div>

    <x-slot:actions>
        <x-mary-button @click="$wire.dancerModal = false">
            Cancelar
        </x-mary-button>

        <x-mary-button 
            icon="o-check" 
            wire:click="{{ $isEditingDancer ? 'updateDancer' : 'addDancer' }}"  
            class="btn-primary" 
            spinner="{{ $isEditingDancer ? 'updateDancer' : 'addDancer' }}"
        >
            {{ $isEditingDancer ? 'Atualizar' : 'Adicionar' }}
        </x-mary-button>
    </x-slot:actions>
</x-mary-modal>


{{-- Modal de Confirmação de Exclusão --}}
<x-mary-modal wire:model="deleteConfirmModal" title="Confirmar Exclusão" class="backdrop-blur">
    <div class="py-4">
        <div class="flex items-center gap-3 mb-4">
            <div class="flex-shrink-0">
                <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.994-.833-2.464 0L3.349 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                </svg>
            </div>
            <div>
                <p class="text-gray-900 font-medium">Tem certeza que deseja remover este Bailarino(a)?</p>
                <p class="text-gray-600 text-sm mt-1">Esta ação não pode ser desfeita.</p>
            </div>
        </div>
    </div>
    <x-slot:actions>
        <x-mary-button @click="$wire.deleteConfirmModal = false">
            Cancelar
        </x-mary-button>

        <x-mary-button 
            icon="o-trash" 
            wire:click="confirmRemoveDancer" 
            class="btn-error" 
            spinner="confirmRemoveDancer"
        >
            Excluir
        </x-mary-button>
    </x-slot:actions>
</x-mary-modal>