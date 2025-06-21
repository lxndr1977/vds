<div class="grid grid-cols-1 md:grid-cols-2 mb-2 md:mb-8">
   <div class="mb-6 col-span-2 md:col-span-1">
      <h2 class="text-xl md:text-2xl font-medium mb-1">Etapa 6: Coreografias</h2>
      <p class="text-zinc-700">Cadastre todos os coreógrafos que participarão do evento.</p>
   </div>
   <div class="mb-6 col-span-2 md:col-span-1 text-start md:text-end">
      <x-mary-button icon="o-plus" wire:click="resetChoreographyForm" @click="$wire.choreographyModal = true"
         class="btn-primary w-auto">
         Adicionar Coreografia
      </x-mary-button>
   </div>
</div>

{{-- Lista de coreografias cadastradas responsiva --}}
<div class="flex items-center gap-2  mb-4">
   <h3 class="text-lg font-semibold">Coreografias Cadastradas</h3>

   @if ($choreographies->count() > 0)
      <x-mary-badge value="{{ $dancers->count() }}" class="badge-soft badge-sm indicator-item" />
   @endif
</div>

@if ($choreographies->count() > 0)
   <div class="space-y-3">
      @foreach ($choreographies as $choreography)
         <div class="bg-white border border-zinc-200 rounded-lg p-4 hover:shadow-md transition-shadow">
            <div class="">
               {{-- Informações da Coreografia --}}
               <div class="flex items-center justify-between mb-3">
                  <div class="flex-1 min-w-0">
                     <p class="font-medium text-zinc-900 line-clamp-1">{{ $choreography->name }}</p>
                     <p class="text-sm text-zinc-600 line-clamp-1">
                        {{ $choreography->choreographyType->name }} | {{ $choreography->dancers->count() }}
                        dançarino(s) | {{ $choreography->choreographers->count() }} coreógrafo(s)
                     </p>
                  </div>

                  {{-- Botões de Ação --}}
                  <div class="flex gap-2 ml-4">
                     <x-mary-button icon="o-pencil" wire:click="editChoreography({{ $choreography->id }})"
                        spinner
                        class="btn-square btn-xs md:btn-sm btn-ghost" title="Editar coreografia" />
                     <x-mary-button icon="o-trash"
                        wire:click="openDeleteChoreographyConfirm({{ $choreography->id }})" spinner
                        class="btn-square btn-xs md:btn-sm btn-ghost" title="Remover coreografia" />
                  </div>
               </div>

               {{-- Links de Gerenciamento --}}
               <div class="mb-3">
                  @if ($choreography->dancers->count() == 0)
                     <span class="w-auto text-red-500 text-sm font-medium">
                        Adicione bailarinos(as) nesta coreografia
                     </span>
                  @endif
               </div>

               <div class="flex gap-4 text-sm">
                  <x-mary-button icon="o-users"
                     wire:click="selectChoreographyForChoreographers({{ $choreography->id }})"
                     class="font-medium btn-primary btn-soft ">
                     Coreógrafos
                  </x-mary-button>
                  <x-mary-button icon="o-users" wire:click="selectChoreographyForDancers({{ $choreography->id }})"
                     class="font-medium btn-primary btn-soft">
                     Bailarino(as)
                  </x-mary-button>
               </div>
            </div>
         </div>
      @endforeach
   </div>
@else
   <div class="bg-zinc-50 border border-zinc-200 rounded-lg p-8 text-center">
      <div class="text-zinc-400 mb-2">
         <svg class="w-12 h-12 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
               d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3">
            </path>
         </svg>
      </div>
      <p class="text-zinc-500">Nenhuma coreografia cadastrada ainda</p>
      <p class="text-sm text-zinc-400 mt-1 mb-6">Clique em "Adicionar Coreografia" para começar</p>
      <x-mary-button icon="o-plus" wire:click="resetChoreographyForm" @click="$wire.choreographyModal = true"
         class="btn-primary w-auto">
         Adicionar Coreografia
      </x-mary-button>
   </div>
@endif

@include('livewire.site.steps.modals.choreography.add-or-edit')

@include('livewire.site.steps.modals.choreography.delete')

@include('livewire.site.steps.modals.choreography.manage-choreographers')

@include('livewire.site.steps.modals.choreography.manage-dancers')
