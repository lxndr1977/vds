{{-- Modal de Confirmação de Exclusão --}}
<x-mary-modal wire:model="deleteChoreographyModal" title="Confirmar Exclusão" class="backdrop-blur">
   <div class="py-4">
      <div class="flex items-center gap-3 mb-4">
         <div class="flex-shrink-0">
            <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
               <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.994-.833-2.464 0L3.349 16.5c-.77.833.192 2.5 1.732 2.5z">
               </path>
            </svg>
         </div>
         <div>
            <p class="text-zinc-900 font-medium">Tem certeza que deseja remover esta coreografia?</p>
            <p class="text-zinc-600 text-sm mt-1">Esta ação não pode ser desfeita.</p>
         </div>
      </div>
   </div>
   <x-slot:actions>
      <x-mary-button @click="$wire.deleteChoreographyModal = false">
         Cancelar
      </x-mary-button>

      <x-mary-button icon="o-trash" wire:click="confirmRemoveChoreography" class="btn-error"
         spinner="confirmRemoveChoreography">
         Excluir
      </x-mary-button>
   </x-slot:actions>
</x-mary-modal>