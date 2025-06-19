
{{-- Modal para Gerenciar Coreógrafos --}}
<x-mary-modal wire:model="manageChoreographersModal" title="Gerenciar Coreógrafos(as)" class="backdrop-blur"
   box-class="max-w-2xl w-full mx-4">
   @if ($selectedChoreographyId)
      <div class="mb-4">
         <span class="text-sm text-zinc-700">Coreografia</span>
         <h4 class="text-lg font-semibold">{{ $choreographies->find($selectedChoreographyId)?->name }}</h4>
      </div>

      <div> 
         <h4 class="font-semibold mb-2">Selecionar Coreógrafos(as)</h4>

         {{-- Campo de pesquisa para coreógrafos --}}
         <div class="mb-3">
            <x-mary-input
               class="focus:ring-primary-600"
               wire:model.live="choreographerSearch"
               placeholder="Pesquisar coreógrafo(a)..."
               icon="o-magnifying-glass"
               clearable />
         </div>

         <div class="h-60 xl:h-80 overflow-y-auto border rounded-md p-3 space-y-2 bg-zinc-50">
            @forelse($this->filteredChoreographers as $choreographer)
               <label class="flex items-center p-2 hover:bg-zinc-200 rounded cursor-pointer"
                  wire:key="choreographer-{{ $choreographer->id }}">
                  <input type="checkbox" wire:model="choreographersForChoreography"
                     value="{{ $choreographer->id }}"
                     class="rounded border-zinc-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                  <span class="ml-3">{{ $choreographer->name }}</span>
               </label>
            @empty
               <p class="text-zinc-500 text-sm text-center py-4">
                  @if ($choreographerSearch)
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

      <x-mary-button icon="o-check" wire:click="syncChoreographers" class="btn-primary"
         spinner="syncChoreographers">
         Salvar Coreógrafos
      </x-mary-button>
   </x-slot:actions>
</x-mary-modal>