
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
