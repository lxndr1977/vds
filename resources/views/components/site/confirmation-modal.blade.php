@props([
    'show' => 'showModal',
    'title' => 'Confirmar ação',
    'confirmAction' => 'confirm',
    'cancelAction' => 'closeModal',
    'confirmText' => 'Confirmar',
    'cancelText' => 'Cancelar',
    'classConfirmAction' => 'primary'
])

<div>
    <x-mary-modal wire:model="{{ $show }}" title="{{ $title }}" @close="$wire.closeModal()">
    
        {{ $slot }}
        
        <x-slot:actions>
            <x-mary-button wire:click="{{ $cancelAction }}">
                {{ $cancelText }}
            </x-mary-button>
            <x-mary-button wire:click="{{ $confirmAction }}" class="{{ $classConfirmAction }}" spinner="{{ $confirmAction }}">
                {{ $confirmText }}
            </x-mary-button>
        </x-slot:actions>
    </x-mary-modal>
</div>