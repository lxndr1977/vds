<div
   class="sidebar w-full md:w-80 xl:w-94 bg-secondary-600  flex-shrink-0 h-auto lg:h-screen md:flex md:flex-col md:justify-between">
   <div class="p-6">
      <div class=" md:block mb-6">
         <div class="md:h-10 xl:h-16 flex justify-center md:justify-start">
            <img src="{{ $systemConfig?->logo_url ?? asset('images/logo-vds-2025.png') }}"
               alt="{{ $systemConfig?->festival_name ?? config('app.name') }}" width="900" height="156"
               class="max-h-10 xl:max-h-10 w-auto">
         </div>
      </div>

      <h1 class="text-white text-sm font-bold mb-4 text-center md:text-start md:block mb-6 xl:mb-8">{{ $systemConfig?->festival_name ?? config('app.name') }}
      </h1>

      <div class="flex flex-row justify-around md:flex-col space-y-1 xl:space-y-8 text-white">

         @if ($registrationsOpenToPublic && $canEditAfterSubmission)
         @foreach ($this->steps as $stepNumber => $stepLabel)
         <div
            class="flex items-center space-x-0 md:space-x-3 {{ $currentStep == $stepNumber ? 'md:bg-secondary-700' : '' }} hover:bg-secondary-700 p-0 md:p-2 rounded-lg hover:cursor-pointer "
            data-step="{{ $stepNumber }}" wire:click="goToStep({{ $stepNumber }})">
            <div
               class="w-8 h-8 {{ $currentStep >= $stepNumber ? 'bg-primary-600' : 'bg-secondary-500' }} rounded-full flex items-center justify-center text-sm font-semibold border-2 border-transparent {{ $isFinished && $stepNumber < $totalSteps ? 'opacity-50' : 'opacity-100' }}">
               {{ $stepNumber }}
            </div>
            <span class="font-medium hidden md:inline text-sm xl:text-base">{{ $stepLabel }}</span>
         </div>
         @endforeach
         @endif
      </div>
   </div>

   <div class="p-6 hidden md:block mt-auto">
      <div class="bg-secondary-700 rounded-lg px-2 py-2 flex items-center justify-between">
         <div class="flex items-center">
            <x-mary-dropdown>
               <x-slot:trigger>
                  <x-mary-button icon="o-user" class="btn-circle btn-sm mr-2 bt-" />
               </x-slot:trigger>

               <x-mary-menu-item title="Editar Perfil" @click="$dispatch('openUserModalProfile')" />
               <x-mary-menu-item title="Alterar Senha" @click="$dispatch('openUserPasswordModal')" />
            </x-mary-dropdown>

            <p class="font-semibold text-sm line-clamp-1 text-white">{{ $userName }}</p>
         </div>

         <div>
            <form method="POST" action="{{ route('logout') }}" class="w-full">
               @csrf
               <x-mary-button
                  icon="o-arrow-left-start-on-rectangle"
                  type="submit"
                  class="btn-ghost btn-square text-white hover:text-primary-600"
                  tooltip="Sair" />
            </form>
         </div>
      </div>
   </div>

</div>