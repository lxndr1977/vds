{{-- Modal para Gerenciar Bailarino(as) --}}
<x-mary-modal wire:model="manageDancersModal" title="Gerenciar Bailarino(as)" class="backdrop-blur"
   box-class="max-w-2xl w-full mx-4">
   @if ($selectedChoreographyId)
      <div class="mb-4">
         <span class="text-sm text-zinc-700">Coreografia</span>
         <h4 class="text-lg font-semibold">{{ $choreographies->find($selectedChoreographyId)?->name }}</h4>
      </div>

      @if (session('error'))
         <x-mary-alert title="{{ session('error') }}" icon="o-exclamation-triangle"
            class="mb-2 bg-red-500 text-white" />
      @endif

      <div>
         <h4 class="font-semibold mb-3">Selecionar Bailarino(as)</h4>

         {{-- Campo de pesquisa para bailarinos --}}
         <div class="mb-3">
            <x-mary-input wire:model.live="dancerSearch" placeholder="Pesquisar bailarino..."
               icon="o-magnifying-glass"
               clearable />
         </div>

         <div class="h-60 xl:h-80 overflow-y-auto border rounded-md p-3 space-y-2 bg-zinc-50">
            @forelse($this->filteredDancers as $dancer)
               <label class="flex items-center p-2 hover:bg-white rounded cursor-pointer"
                  wire:key="dancer-{{ $dancer->id }}">
                  <input type="checkbox" wire:model="dancersForChoreography" value="{{ $dancer->id }}"
                     class="rounded border-zinc-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                  <span class="ml-3 ">{{ $dancer->name }}</span>
               </label>
            @empty
               <p class="text-zinc-500 text-sm text-center py-4">
                  @if ($dancerSearch)
                     Nenhum bailarino encontrado para "{{ $dancerSearch }}"
                  @else
                     Nenhum bailarino disponível
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

      <x-mary-button icon="o-check" wire:click="syncDancers" class="btn-primary" spinner="syncDancers">
         Salvar Bailarino(as)
      </x-mary-button>
   </x-slot:actions>
</x-mary-modal>