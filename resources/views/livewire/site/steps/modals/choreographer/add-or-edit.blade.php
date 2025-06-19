
{{-- Modal para Adicionar/Editar Coreógrafo --}}
<x-mary-modal
    wire:model="choreographerModal" 
    :title="$isEditingChoreographer ? 'Editar coreógrafo(a)' : 'Cadastrar coreógrafo(a)'" 
    class="backdrop-blur"
    @close="$wire.closeChoreographerModal()"
>
        <div class="grid grid-cols-1 gap-4">
            <div 
                x-data 
                @input="$wire.clearError('choreographerState.name')"
            >                        
                <x-mary-input 
                    label="Nome do(a) Coreógrafo(a)" 
                    wire:model.lazy="choreographerState.name" 
                    id="choreographer_name"
                    placeholder="Nome" 
                    error-class="font-bold text-red-600" 
                />
            </div>

           <div x-data="{
                    public_domain: @entangle('choreographerState.is_public_domain').live,
                    attending: @entangle('choreographerState.is_attending').live,
                    adaptation: @entangle('choreographerState.is_adaptation').live
                }"
                class="space-y-3"
            >
                <div @input="$wire.clearError('choreographerState.is_public_domain')">
                    <x-mary-toggle 
                        label="É de domínio público?" 
                        x-model="public_domain"
                        @change="if (public_domain) attending = false"
                        id="is_public_domain" 
                    />
                </div>

                <div @input="$wire.clearError('choreographerState.is_adaptation')">
                    <x-mary-toggle 
                        label="É responsável por adaptação?" 
                        x-model="adaptation"
                        id="is_adaptation" 
                    />
                </div>

                <div @input="$wire.clearError('choreographerState.is_attending')">
                    <x-mary-toggle 
                        label="Participará presencialmente do evento?" 
                        x-model="attending"
                        @change="if (attending) public_domain = false"
                        id="is_attending" 
                    />
                </div>
            </div>
        </div>
    <x-slot:actions>
        <x-mary-button 
            icon="o-x-mark" 
            @click="$wire.closeChoreographerModal()"
        >
            Cancelar
        </x-mary-button>

        @if($isEditingChoreographer)
            <x-mary-button 
                icon="o-check" 
                wire:click="updateChoreographer" 
                class="btn-primary" 
                spinner="updateChoreographer"
            >
                Atualizar
            </x-mary-button>
        @else
            <x-mary-button 
                icon="o-check" 
                wire:click="addChoreographer" 
                class="btn-primary" 
                spinner="addChoreographer"
            >
                Adicionar
            </x-mary-button>
        @endif
    </x-slot:actions>
</x-mary-modal>