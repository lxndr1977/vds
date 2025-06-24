<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}"  data-theme="light" class="light">
    <head>
        @include('partials.head')
    </head>
    <body class="min-h-screen light">
        <x-mary-toast />  

        <div>
            {{ $slot }}
        </div>
    </body>
</html>