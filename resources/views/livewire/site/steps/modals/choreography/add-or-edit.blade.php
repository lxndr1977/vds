
<x-mary-modal
   wire:model="choreographyModal"
   title="{{ $isEditingChoreography ? 'Editar Coreografia' : 'Cadastrar Coreografia' }}"
   class="backdrop-blur modal-box-lg">
   <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
      <div class="col-span-3">
         <div x-data @input="$wire.clearError('choreographyState.name')">
            <x-mary-input label="Nome da Coreografia" wire:model.lazy="choreographyState.name" id="name"
               placeholder="Nome da Coreografia" error-class="font-bold text-red-600" />
         </div>
      </div>

      {{-- Projetos Sociais/Universitários --}}
      <div class="md:col-span-3 flex flex-col md:flex-row items-center space-y-6 md:space-y-0 md:space-x-8">
         <div x-data @input="$wire.clearError('choreographyState.is_social_project')">
            <x-mary-toggle label="É um projeto social?" wire:model.defer="choreographyState.is_social_project"
               id="is_social_project" />
         </div>

         <div x-data @input="$wire.clearError('choreographyState.is_university_project')">
            <x-mary-toggle label="É um projeto universitário?"
               wire:model.defer="choreographyState.is_university_project" id="is_university_project" />
         </div>
      </div>

      <div>
         <div x-data @input="$wire.clearError('choreographyState.choreography_type_id')">
            <x-mary-select label="Formação" wire:model.lazy="choreographyState.choreography_type_id"
               id="choreography_type_id" :options="$choreographyTypes" placeholder="Selecione" placeholder-value="0"
               error-class="font-bold text-red-600" />
         </div>
      </div>

      <div>
         <div x-data @input="$wire.clearError('choreographyState.choreography_category_id')">
            <x-mary-select label="Categoria" wire:model.lazy="choreographyState.choreography_category_id"
               id="choreography_category_id" :options="$choreographyCategories" placeholder="Selecione"
               placeholder-value="0" error-class="font-bold text-red-600" />
         </div>
      </div>

      <div>
         <div x-data @input="$wire.clearError('choreographyState.dance_style_id')">
            <x-mary-select label="Modalidade" wire:model.lazy="choreographyState.dance_style_id" id="dance_style_id"
               :options="$danceStyles" placeholder="Selecione" placeholder-value="0"
               error-class="font-bold text-red-600" />
         </div>
      </div>

      <div>
         <div x-data @input="$wire.clearError('choreographyState.music')">
            <x-mary-input label="Música" wire:model.lazy="choreographyState.music" id="music"
               placeholder="Música"
               error-class="font-bold text-red-600" />
         </div>
      </div>

      <div>
         <div x-data @input="$wire.clearError('choreographyState.music_composer')">
            <x-mary-input label="Compositor" wire:model.lazy="choreographyState.music_composer"
               id="music_composer"
               placeholder="Compositor" error-class="font-bold text-red-600" />
         </div>
      </div>

      <div>
         <div x-data="{
             formatDuration(value) {
                 if (!value) return '';
                 let v = value.replace(/\D/g, '').substring(0, 4);
                 if (v.length >= 3) {
                     v = v.replace(/(\d{2})(\d{2})/, '$1:$2');
                 } else if (v.length >= 2) {
                     v = v.replace(/(\d{2})/, '$1:');
                 }
                 return v;
             }
         }" x-init="$nextTick(() => {
             let input = $el.querySelector('input');
             if (input && input.value) {
                 input.value = formatDuration(input.value);
             }
         })"
            @input="$wire.clearError('choreographyState.duration')">
            <x-mary-input label="Duração" wire:model.lazy="choreographyState.duration" id="duration"
               placeholder="MM:SS"
               error-class="font-bold text-red-600"
               x-on:input="
                            let v = formatDuration($event.target.value);
                            $event.target.value = v;
                            $wire.set('choreographyState.duration', v);
                        "
               maxlength="5" />
         </div>
      </div>
   </div>

   <x-slot:actions>
      <x-mary-button @click="$wire.choreographyModal = false">
         Cancelar
      </x-mary-button>

      <x-mary-button icon="o-check"
         wire:click="{{ $isEditingChoreography ? 'updateChoreography' : 'addChoreography' }}"
         class="btn-primary"
         spinner="{{ $isEditingChoreography ? 'updateChoreography' : 'addChoreography' }}">
         {{ $isEditingChoreography ? 'Atualizar' : 'Adicionar' }}
      </x-mary-button>
   </x-slot:actions>
</x-mary-modal>
