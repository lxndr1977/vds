<div>
    <div class="mb-6">
        <h2 class="text-xl md:text-2xl font-medium mb-1">Etapa 1: Dados da Escola</h2>
        <p class="text-sm">Preencha as informações da sua escola, grupo ou companhia.</p>
    </div>

    <form wire:submit.prevent="saveSchool">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
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

            {{-- Projetos Sociais/Universitários --}}
            <div class="md:col-span-2 flex flex-col md:flex-row items-center space-y-6 md:space-y-0 md:space-x-8">
                <div 
                    x-data 
                    @input="$wire.clearError('schoolState.is_social_project')"
                >
                    <x-mary-toggle 
                        label="É um projeto social?" 
                        wire:model.defer="schoolState.is_social_project" 
                        id="is_social_project" 
                    />
                </div>

                <div 
                    x-data 
                    @input="$wire.clearError('schoolState.is_university_project')"
                >
                    <x-mary-toggle 
                        label="É um projeto universitário?" 
                        wire:model.defer="schoolState.is_university_project" 
                        id="is_university_project" 
                    />
                </div>
            </div>
        </div>

        {{-- Endereço --}}
        <div class="grid grid-cols-1 md:grid-cols-4 py-6">
            <div 
                x-data 
                @input="$wire.clearError('schoolState.zip_code')"
            >
                <x-mary-input 
                    label="CEP"
                    wire:model.defer="schoolState.zip_code"
                    id="zip_code"
                    placeholder="CEP"
                    error-class="font-bold text-red-600"
                />
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div 
                x-data 
                @input="$wire.clearError('schoolState.street')"
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
            >
                <x-mary-input 
                    label="Estado" 
                    wire:model.defer="schoolState.state" 
                    id="state"
                    placeholder="Estado" 
                    error-class="font-bold text-red-600"
                />
            </div>
        </div>
    </form>
</div>
