@if ($registrationsOpenToPublic)
   <footer class="fixed bottom-0 left-0 w-full lg:relative bg-white border-t border-gray-200 p-4 mt-auto">
      <div class="max-w-4xl xl:max-w-6xl mx-auto grid grid-cols-2 md:grid-cols-3">

         <div>
            @if ($currentStep > 1 && !$isFinished)
               <x-mary-button
                  class="btn-primary"
                  icon="o-chevron-left"
                  wire:click="previousStep"
                  spinner="previousStep">
                  Voltar
               </x-mary-button>
            @endif

            @if ($currentStep == 6 && $isFinished && $registrationsOpenToPublic)
               <x-mary-button
                  class="btn btn-primary"
                  icon="o-pencil"
                  @click="$wire.showReopenRegistrationModal = true">
                  Editar <span class="hidden md:inline-block">Inscrição</span>
                  <x-mary-loading wire:loading wire:target="reopenRegistration" class="text-white" />
               </x-mary-button>
            @endif
         </div>

         <div class="hidden sm:flex">
            <div class="{{ $isFinished ? 'sm:hidden' : 'sm:flex ' }} justify-center items-center gap-1">
               <span class="text-xs sm:text-sm text-gray-600">Etapa</span>
               <span class="text-xs sm:text-sm text-gray-600"
                  id="current-step-indicator">{{ $currentStep }}</span>
               <span class="text-xs sm:text-sm text-gray-600">de 6</span>
               <div class="w-24 md:w-32 bg-gray-200 rounded-full h-2 ml-2 md:ml-4">
                  <div
                     class="bg-primary-600 h-2 rounded-full transition-all duration-300"
                     id="progress-bar"
                     style="width: {{ ($currentStep / $totalSteps) * 100 }}%"></div>
               </div>
            </div>
         </div>

         <div class="text-right">
            @if ($currentStep == 1)
               <x-mary-button
                  class="btn-primary"
                  wire:click="saveSchool"
                  icon-right="o-arrow-right"
                  type="submit"
                  form="form-step-1"
                  spinner="saveSchool">
                  Salvar <span class="hidden md:inline-block">e continuar</span>
               </x-mary-button>
            @endif

            @if ($currentStep >= 2 && $currentStep < 6)
               <x-mary-button
                  class="btn-primary"
                  icon-right="o-chevron-right"
                  wire:click="nextStep"
                  spinner="nextStep">
                  Avançar
               </x-mary-button>
            @endif

            @if ($currentStep == 6 && !$isFinished)
               <x-mary-button
                  @click="$wire.showConfirmationModal = true"
                  class="btn btn-primary"
                  icon="o-check">
                  Finalizar <span class="hidden md:inline-block">Inscrição</span>
                  <x-mary-loading wire:loading wire:target="finishRegistration" class="text-white" />
               </x-mary-button>
            @endif

            @if ($currentStep == 6 && $isFinished)
               <x-mary-button
                  link="{{ $this->getLinkPayment() }}"
                  external
                  target="_blank"
                  icon="o-check"
                  class="btn-primary">
                  Pagar <span class="hidden md:inline-block">Efetuar Pagamento</span>
               </x-mary-button>
            @endif
         </div>
      </div>
   </footer>
@endif
