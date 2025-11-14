<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-gray-900 antialiased">
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gradient-to-br from-sky-100 via-white to-indigo-50 animate-fade-in">
            <div class="mb-6 transform hover:scale-105 transition duration-500">
                <a href="/" class="flex items-center space-x-3">
                    <x-application-logo class="w-20 h-20 fill-current text-indigo-600" />
                    <span class="text-2xl font-extrabold text-indigo-700">{{ config('app.name', 'CampusConnect') }}</span>
                </a>
            </div>

            <div class="w-full sm:max-w-md mt-0 px-6 py-8 bg-white/80 backdrop-blur-sm shadow-xl overflow-hidden sm:rounded-2xl border border-white/60">
                {{ $slot }}
            </div>
        </div>
    </body>
</html>
