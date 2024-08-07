<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>{{ $title ?? 'Simple Ecommerce' }}</title>
        <style>
        </style>
        @livewireStyles
        @vite(['resources/css/app.css'])
    </head>
    <body class="bg-slate-200 dark:bg-slate-700">
        <main>
            {{ $slot }}
        </main>
        @livewireScripts
        @vite(['resources/js/app.js'])
    </body>
</html>
