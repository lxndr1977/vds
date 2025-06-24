{{-- Definir o data-theme apenas na tag <html> é suficiente --}}
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-theme="light" class="light">
    <head>
        @include('partials.head')
    </head>
    
    {{-- Remova a classe 'dark:*' e o 'data-theme' redundante do body --}}
    <body class="min-h-screen light">
        <x-mary-toast />  

        <livewire:site.registration-form />

        <div>
            {{ $slot }}
        </div>
    </body>
</html>