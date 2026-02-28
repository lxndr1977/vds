@php
   $config = $systemConfig ?? \App\Models\SystemConfiguration::current();
   $festival = $config?->festival_name ?? config('app.name');
   $logo = $config?->logo_url ?? asset('logo-vds-2025-colorido.jpg');
@endphp

<x-layouts.app :title="$festival">
   <div class="min-h-screen grid grid-cols-1items-center">
      <div class="bg-secondary-600 h-full">
         <div class="bg-muted flex min-h-svh flex-col items-center justify-center gap-6 p-6 md:p-10">
            <div class="flex w-full max-w-md flex-col gap-6">
               <a href="{{ route('home') }}" class="flex flex-col items-center gap-2 font-medium" wire:navigate>
                  <img src="{{ $logo }}" class="max-w-64" alt="{{ $festival }}">
                  <span class="sr-only">{{ $festival }}</span>
               </a>
               <div class="flex flex-col gap-6">
                  <div class="rounded-xl border bg-white text-stone-800 shadow-xs">
                     <div class="px-10 py-8 text-center">
                        <h1 class="text-xl font-bold text-primary-600">{{ $festival }}</h1>
                        <p class="mb-6 text-gray-600">Bem-vindo(a) ao {{ $festival }}!</p>

                        <div class="flex flex-col gap-4">

                           @if($config?->registrations_open_to_public ?? true)
                              <p class="text-sm text-gray-700">Se você já iniciou sua inscrição e deseja continuar de onde parou, faça login.</p>
                           @else
                              <p class="text-sm text-gray-700">Faça login para visualizar a sua inscrição.</p>
                           @endif

                           <x-mary-button link="{{ route('login') }}" class="btn-primary">
                              Fazer login
                           </x-mary-button>

                           @if($config?->registrations_open_to_public ?? true)
                              <div class="flex items-center my-4">
                                 <div class="flex-1 border-t border-gray-300"></div>
                                 <span class="px-4 text-gray-500 text-sm">ou</span>
                                 <div class="flex-1 border-t border-gray-300"></div>
                              </div>

                              <p class="text-sm text-gray-700">Se esse é o seu primeiro acesso, crie uma conta para iniciar a inscrição.</p>

                              <x-mary-button link="{{ route('register') }}" class="btn-outline">
                                 Criar conta
                              </x-mary-button>
                           @endif
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
</x-layouts.app>