<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        @include('partials.head')
    </head>
    <body class="min-h-screen dark:bg-zinc-800">
        <x-mary-toast />  

        <div class="bg-primary-600 w-full sticky z-50">
            <x-mary-nav class="bg-primary-600 max-w-7xl mx-auto z-[9999999]">
                <x-slot:brand>
                    <div class="">
                        <a href="{{ route('site') }}"><img src="/logo-vds-2025.png" alt="Logo" class="max-w-48"></a>
                    </div>
                </x-slot:brand>
                <x-slot:actions>
                    <label for="main-drawer" class="lg:hidden mr-3">
                        <x-mary-icon name="o-bars-3" class="cursor-pointer" />
                    </label>

                        <x-mary-dropdown
                            title="Minha Conta" 
                            activate-by-route
                            icon="o-user"
                            class="btn-circle">
                            <form method="POST" action="{{ route('logout') }}" class="w-full">
                                @csrf
                                <x-mary-menu-item title="Minha Conta" link="{{route('settings.profile')}}" />
                                <x-mary-button type="submit" class="btn-ghost text-zinc-900">Sair</x-mary-button>
                            </form>
                        </x-mary-dropdown>
                    </div>
                </x-slot:actions>
            </x-mary-nav>
        </div>

        <div>
            {{ $slot }}
        </div>
    </body>
</html>