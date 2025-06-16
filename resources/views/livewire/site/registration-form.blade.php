{{--
    Este é o container principal para o formulário de inscrição.
    Ele gerencia a exibição do progresso e da etapa atual.
--}}
{{-- Adicionado 'pb-28' (padding-bottom) para criar espaço para a barra de rodapé fixa e evitar que o conteúdo seja sobreposto --}}
<div class="">
    {{-- Notificações com Alpine.js --}}
    <div
        x-data="{ show: false, message: '', type: '' }"
        x-on:notify.window="show = true; message = $event.detail.message; type = $event.detail.type; setTimeout(() => show = false, 5000)"
        x-show="show"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 transform translate-y-2"
        x-transition:enter-end="opacity-100 transform translate-y-0"
        x-transition:leave="transition ease-in duration-300"
        x-transition:leave-start="opacity-100 transform translate-y-0"
        x-transition:leave-end="opacity-0 transform translate-y-2"
        :class="{ 'bg-green-400': type === 'success', 'bg-red-500': type === 'error', 'bg-primary-500': type === 'info', 'bg-yellow-500': type === 'warning' }"
        class="fixed top-5 right-5 text-white px-4 py-2 rounded-lg shadow-lg z-50"
        style="display: none;"
    >
        <span x-text="message"></span>
    </div>

    <x-mary-toast />  
    @if ($status === 'cancelled')
        <div class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4" role="alert">
            <p>Esta inscrição foi cancelada e não pode ser editada.</p>
        </div>
    @else
        @if ($status === 'finished')
            {{-- Apenas mostra o resumo final, sem navegação --}}
            <div class="max-w-5xl mx-auto mt-12">
                <livewire:site.registration-summary  />
            </div>
        @else
            {{-- Formulário normal com navegação e etapas --}}
            <div class="z-10 mb-8 md:mb-12 flex justify-center sticky top-0 shadow-sm py-2 md:py-4 w-full bg-white border-b border-zinc-100 transition-all duration-300 ease-in">
                <div class="flex items-center space-x-2 md:space-x-4">
                    @php
                        $steps = [
                            1 => 'Escola',
                            2 => 'Equipe',
                            3 => 'Coreógrafos',
                            4 => 'Bailarinos',
                            5 => 'Coreografias',
                            6 => 'Finalizar',
                        ];
                    @endphp
                    @foreach ($steps as $step => $title)
                        <div class="flex flex-col items-center">
                            <button
                                @disabled($step > $this->getHighestCompletedStep())
                                class="w-8 h-8 md:w-10 md:h-10 rounded-full flex items-center justify-center text-sm font-medium transition-colors {{ $currentStep >= $step ? 'bg-primary-600 text-white' : 'bg-gray-200 text-gray-600' }} disabled:bg-gray-100 disabled:cursor-not-allowed hover:opacity-80"
                            >
                                {{ $step }}
                            </button>
                            <span class="hidden md:block text-xs mt-2 text-center {{ $currentStep >= $step ? 'text-gray-800 font-medium' : 'text-gray-500' }}">
                                {{ $title }}
                            </span>
                        </div>
                        @if ($step < count($steps))
                            <div class="w-4 md:w-8 h-px bg-gray-300 mt-0 md:mt-4"></div>
                        @endif
                    @endforeach
                </div>
            </div>

            <div class="max-w-5xl mx-auto bg-white p-0 pb-32">
                @if ($currentStep == 1)
                    {{-- Note que o formulário da etapa 1 deve ter a ação de submit apontando para o método 'saveSchool' --}}
                    {{-- Ex: <form wire:submit="saveSchool"> ... </form> --}}
                    @include('livewire.site.steps.school')
                @elseif ($currentStep == 2)
                    @include('livewire.site.steps.members')
                @elseif ($currentStep == 3)
                    @include('livewire.site.steps.choreographers')
                @elseif ($currentStep == 4)
                    @include('livewire.site.steps.dancers')
                @elseif ($currentStep == 5)
                    @include('livewire.site.steps.choreography')
                @elseif ($currentStep == 6)
                    @include('livewire.site.steps.final')
                @endif
            </div>

            {{-- ================================================================== --}}
            {{-- INÍCIO DA BARRA DE NAVEGAÇÃO FIXA E CONDICIONAL                  --}}
            {{-- ================================================================== --}}
            <div class="fixed bottom-0 left-0 w-full bg-base-100/80 backdrop-blur-sm border-t p-4 z-40">
                <div class="max-w-5xl mx-auto">
                    {{-- ETAPA 1: Apenas botão para Salvar e Continuar --}}
                    @if ($currentStep == 1)
                        <div class="flex justify-end">
                            {{-- Para este botão funcionar, o @include('livewire.site.steps.school') deve estar dentro de uma tag <form> --}}
                            {{-- Ex: <form wire:submit="saveSchool"> ... SEU FORMULÁRIO ... </form> --}}
                            <x-mary-button class="btn-primary" wire:click="saveSchool" icon-right="o-arrow-right" type="submit" form="form-step-1" spinner="saveSchool">Salvar e continuar</x-mary-button>
                        </div>
                    
                    {{-- ETAPAS 2 a 5: Botões de Voltar e Avançar --}}
                    @elseif ($currentStep >= 2 && $currentStep < 6)
                        <div class="flex justify-between">
                            <x-mary-button 
                                icon="o-chevron-left" 
                                wire:click="previousStep"
                                spinner="previousStep">
                                    Voltar
                            </x-mary-button>
                    
                            <x-mary-button 
                                class="btn-primary" 
                                icon-right="o-chevron-right" 
                                wire:click="nextStep"
                                spinner="nextStep">
                                    Avançar
                            </x-mary-button>
                        </div>

                    {{-- ETAPA 6: Botões de Voltar e Finalizar Inscrição --}}
                    @elseif ($currentStep == 6)
                        <div class="flex justify-between items-center">
                            <x-mary-button
                                icon="o-chevron-left"
                                wire:click="previousStep"
                                spinner="previousStep">
                                Voltar
                            </x-mary-button>

                            <x-mary-button 
                                @click="$wire.showConfirmationModal = true"
                                class="btn btn-primary"
                                icon="o-check"
                                >
                                Finalizar Inscrição
                                <x-mary-loading wire:loading wire:target="finishRegistration" class="text-white" />
                            </x-mary-button>
                        </div>
                    @endif
                </div>
            </div>
            {{-- ================================================================== --}}
            {{-- FIM DA BARRA DE NAVEGAÇÃO FIXA                                     --}}
            {{-- ================================================================== --}}

        @endif
    @endif
</div>
