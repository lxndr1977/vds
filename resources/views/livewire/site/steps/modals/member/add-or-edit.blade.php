<x-mary-modal 
    wire:model="memberModal" 
    :title="$isEditing ? 'Editar Integrante' : 'Cadastrar Integrante'" 
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