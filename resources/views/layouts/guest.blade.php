<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Payke MA') }}</title>

        <!-- Favicon -->
        <link rel="shortcut icon" href="{{ asset('/favicon_v2.png') }}">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-gray-900 antialiased">
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0">
            <div class="flex h-16 shrink-0 items-end">
                <img class="h-14 w-auto" src="{{ asset('/app_icon_v2.png') }}?color=indigo&shade=600" alt="Payke MA">
                <span class="ml-4 mr-4 mb-1 font-mono text-2xl">Payke MA</span>
                <div class="w-8"></div>
            </div>
            <div class="w-full sm:max-w-md mt-4 px-6 pt-6 pb-4 bg-white shadow-md overflow-hidden sm:rounded-lg border-solid border border-gray-400">
                {{ $slot }}
            </div>
        </div>
    </body>
</html>
