<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        @include('partials.head')
    </head>
    <body class="min-h-screen dark:bg-zinc-800">
        <x-mary-toast />  

        <livewire:site.registration-form />

        <div>
            {{ $slot }}
        </div>
    </body>
</html>