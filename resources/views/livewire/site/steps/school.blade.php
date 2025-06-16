<div>
    <div class="mb-6">
        <h2 class="text-xl md:text-2xl font-medium mb-1">Etapa 1: Dados da Grupo/Escola/Cia</h2>
        <p class="text-zinc-700">Preencha as informações de contato do seu Grupo, Escola ou Cia</p>
    </div>

    <form wire:submit.prevent="saveSchool">
        <div class="grid grid-cols-1 md:grid-cols-2 mb-3">
            {{-- Nome da Escola --}}
            <div class="md:col-span-2" 
                x-data 
                @input="$wire.clearError('schoolState.name')"
            >
                <x-mary-input 
                    label="Name da Escola/Grupo/Cia" 
                    wire:model.defer="schoolState.name" 
                    id="school_name"
                    placeholder="Nome da Escola/Grupo/Cia" 
                    error-class="font-bold text-red-600"
                />
            </div>
        </div>

       {{-- Endereço --}}
        <div class="grid grid-cols-1 md:grid-cols-4 mb-3">
            <div 
                x-data="{ 
                    formatZip(value) {
                        if (!value) return '';
                        let v = value.replace(/\D/g, '').substring(0, 8);
                        if (v.length > 5) v = v.substring(0, 5) + '-' + v.substring(5);
                        return v;
                    }
                }"
                x-init="
                    $nextTick(() => {
                        let input = $el.querySelector('input');
                        if (input && input.value) {
                            input.value = formatZip(input.value);
                        }
                    })
                "
                @input="$wire.clearError('schoolState.zip_code')"
            >
                <x-mary-input 
                    label="CEP" 
                    wire:model.defer="schoolState.zip_code" 
                    id="zip_code"
                    placeholder="00000-000" 
                    error-class="font-bold text-red-600"
                    x-on:input="
                        let v = formatZip($event.target.value);
                        $event.target.value = v;
                        $wire.set('schoolState.zip_code', v);
                        if (v.replace('-', '').length === 8) {
                            $wire.searchZipCode();
                        }
                    "
                    maxlength="9"
                />
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-6 gap-3 mb-3">
            <div 
                x-data 
                @input="$wire.clearError('schoolState.street')"
                class="col-span-4"
            >
                <x-mary-input 
                    label="Rua" 
                    wire:model.defer="schoolState.street" 
                    id="street"
                    placeholder="Rua"  
                    error-class="font-bold text-red-600"
                    
                />
            </div>

            <div 
                x-data 
                @input="$wire.clearError('schoolState.number')"
            >
                <x-mary-input 
                    label="Número" 
                    wire:model.defer="schoolState.number" 
                    id="number"
                    placeholder="Número"  
                    error-class="font-bold text-red-600"
                />
            </div>

            <div 
                x-data 
                @input="$wire.clearError('schoolState.complement')"
            >
                <x-mary-input 
                    label="Complemento" 
                    wire:model.defer="schoolState.complement" 
                    id="complement"
                    placeholder="Complemento"  
                    error-class="font-bold text-red-600"
                />
            </div>

            <div 
                x-data 
                @input="$wire.clearError('schoolState.district')"
                class="col-span-2"

            >
                <x-mary-input 
                    label="Bairro" 
                    wire:model.defer="schoolState.district" 
                    id="district"
                    placeholder="Bairro" 
                    error-class="font-bold text-red-600"
                />
            </div>

            <div 
                x-data 
                @input="$wire.clearError('schoolState.city')"
                class="col-span-2"

            >
                <x-mary-input 
                    label="Cidade" 
                    wire:model.defer="schoolState.city" 
                    id="city"
                    placeholder="Cidade" 
                    error-class="font-bold text-red-600"
                />
            </div>

            
            <div 
                x-data 
                @input="$wire.clearError('schoolState.state')"
                class="col-span-2"
            >
                <x-mary-select
                    id="state"
                    label="Estado"
                    wire:model.defer="schoolState.state"
                    :options="$brazilStates"
                    wire:loading.attr="disabled"
                    wire:target="schoolZipCode"  
                />
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
            <div class="md:col-span-2" 
                x-data 
                @input="$wire.clearError('schoolState.responsible_name')"
            >
                <x-mary-input 
                    label="Nome do Responsável pelo Grupo/Escola/Cia" 
                    wire:model.defer="schoolState.responsible_name" 
                    id="responsible_name"
                    placeholder="Responsável" 
                    error-class="font-bold text-red-600"
                />
            </div>

            <div 
                x-data 
                @input="$wire.clearError('schoolState.responsible_email')"
            >
                <x-mary-input 
                label="Email do Responsável" 
                wire:model.lazy="schoolState.responsible_email" 
                id="responsible_email"
                placeholder="Email"  
                error-class="font-bold text-red-600"
                />
            </div>

           <div
                x-data="{ 
                    formatPhone(value) {
                        if (!value) return '';
                        let v = value.replace(/\D/g, '').substring(0, 11);
                        if (v.length >= 11) {
                            // (99) 99999-9999
                            v = v.replace(/(\d{2})(\d{5})(\d{4})/, '($1) $2-$3');
                        } else if (v.length >= 10) {
                            // (99) 9999-9999
                            v = v.replace(/(\d{2})(\d{4})(\d{4})/, '($1) $2-$3');
                        } else if (v.length >= 6) {
                            v = v.replace(/(\d{2})(\d{4})/, '($1) $2');
                        } else if (v.length >= 2) {
                            v = v.replace(/(\d{2})/, '($1) ');
                        }
                        return v;
                    }
                }"
                x-init="
                    $nextTick(() => {
                        let input = $el.querySelector('input');
                        if (input && input.value) {
                            input.value = formatPhone(input.value);
                        }
                    })
                "
                @input="$wire.clearError('schoolState.responsible_phone')"
            >
                <x-mary-input 
                    label="Whatsapp do Responsável" 
                    wire:model.defer="schoolState.responsible_phone" 
                    id="responsible_phone"
                    placeholder="(99) 99999-9999" 
                    error-class="font-bold text-red-600"
                    x-on:input="
                        let v = formatPhone($event.target.value);
                        $event.target.value = v;
                        $wire.set('schoolState.responsible_phone', v);
                    "
                    maxlength="15"
                />
            </div>
        </div>
    </form>
</div>
