<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        @include('partials.head')
    </head>
    <body class="min-h-screen">
        <x-mary-toast />  

        <div>
            {{ $slot }}
        </div>
    </body>
</html>