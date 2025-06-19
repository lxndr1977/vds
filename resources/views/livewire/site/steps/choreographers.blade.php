{{-- Etapa 3: Coreógrafos(as) --}}
<div>
   <div class="flex flex-col md:flex-row  items-start md:items-center justify-start md:justify-between mb-8">
      <div class="mb-6">
         <h2 class="text-xl md:text-2xl font-medium mb-1">Etapa 3: Coreógrafos(as)</h2>
         <p class="text-zinc-700">Cadastre todos os Coreógrafos(as) do Grupo/Escola/Cia</p>
      </div>

      <div class="text-left md:text-right mt-4 mb-8">
         <x-mary-button icon="o-plus" @click="$wire.choreographerModal = true" class="btn-primary">
            Adicionar Coreógrafo(a)
         </x-mary-button>
      </div>
   </div>

   {{-- Lista de coreógrafos cadastrados responsiva --}}
   <div class="flex items-center gap-2  mb-4">
      <h3 class="text-lg font-semibold">Coreógrafos(as) Cadastrados</h3>

      @if ($choreographers->count() > 0)
         <x-mary-badge value="{{ $choreographers->count() }}" class="badge-soft badge-sm indicator-item" />
      @endif
   </div>

   @if ($choreographers->count() > 0)
      <div class="space-y-3">
         @foreach ($choreographers as $choreographer)
            <div class="bg-white border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow">
               <div class="flex items-center justify-between">
                  {{-- Informações do Coreógrafo --}}
                  <div class="space-y-1">
                     <div class="flex-1 min-w-0">
                        <p class="font-medium text-gray-900 line-clamp-1">{{ $choreographer->name }}</p>
                     </div>
                     <div class="flex flex-col md:flex-row gap-2">
                        @if ($choreographer->is_public_domain)
                           <x-mary-badge value="Domínio público" class="badge-sm badge-soft font-bold" />
                        @endif
                        @if ($choreographer->is_adaptation)
                           <x-mary-badge value="Responsável por adaptação" class="badge-sm badge-soft font-bold" />
                        @endif
                        @if ($choreographer->is_attending)
                           <x-mary-badge value="Participará presencialmente" class="badge-sm badge-soft font-bold" />
                        @endif
                     </div>
                  </div>

                  {{-- Botões de Ação --}}
                  <div class="flex gap-2 ml-4">
                     <x-mary-button
                        icon="o-pencil"
                        wire:click="editChoreographer({{ $choreographer->id }})"
                        spinner
                        class="btn-square btn-xs md:btn-sm btn-ghost"
                        tooltip="Editar"
                        title="Editar coreógrafo(a)" />
                     <x-mary-button
                        icon="o-trash"
                        wire:click="prepareDeleteChoreographer({{ $choreographer->id }})"
                        spinner
                        class="btn-square btn-xs md:btn-sm btn-ghost"
                        tooltip="Excluir"
                        title="Remover coreógrafo(a)" />
                  </div>
               </div>
            </div>
         @endforeach
      </div>
   @else
      <div class="bg-gray-50 border border-gray-200 rounded-lg p-8 text-center">
         <div class="text-gray-400 mb-2">
            <svg class="w-12 h-12 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
               <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                  d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z">
               </path>
            </svg>
         </div>
         <p class="text-gray-500">Nenhum coreógrafo(a) cadastrado ainda</p>
         <p class="text-sm text-gray-400 mt-1 mb-6">Clique em "Adicionar Coreógrafo(a)" para começar</p>
         <x-mary-button icon="o-plus" @click="$wire.choreographerModal = true" class="btn-primary">
            Adicionar Coreógrafo(a)
         </x-mary-button>
      </div>
   @endif

   @include('livewire.site.steps.modals.choreographer.add-or-edit')
   @include('livewire.site.steps.modals.choreographer.delete')
