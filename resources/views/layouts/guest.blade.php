<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'VideoConnect') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=outfit:400,500,600,700&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <style>
             body { font-family: 'Outfit', sans-serif; }
        </style>
    </head>
    <body class="h-full antialiased font-sans text-gray-900 bg-gray-900 selection:bg-indigo-500 selection:text-white">
        <!-- Colorful Mesh Gradient Background -->
        <div class="fixed inset-0 -z-10 overflow-hidden">
            <div class="absolute inset-0 bg-gray-900"></div>
            <div class="absolute top-0 left-1/2 -translate-x-1/2 w-[1000px] h-[600px] bg-indigo-600/30 rounded-full blur-3xl opacity-50 mix-blend-screen animate-blob"></div>
            <div class="absolute bottom-0 right-0 w-[800px] h-[600px] bg-violet-600/30 rounded-full blur-3xl opacity-50 mix-blend-screen animate-blob animation-delay-2000"></div>
            <div class="absolute bottom-1/4 left-0 w-[600px] h-[600px] bg-pink-600/20 rounded-full blur-3xl opacity-40 mix-blend-screen animate-blob animation-delay-4000"></div>
        </div>

        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 px-4">
            <div class="mb-8">
                <a href="/" class="flex flex-col items-center group">
                    <div class="bg-white/10 p-3 rounded-xl backdrop-blur-sm border border-white/10 group-hover:bg-white/20 transition duration-300">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-10 h-10 text-white">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5l4.72-4.72a.75.75 0 011.28.53v11.38a.75.75 0 01-1.28.53l-4.72-4.72M4.5 18.75h9a2.25 2.25 0 002.25-2.25v-9a2.25 2.25 0 00-2.25-2.25h-9A2.25 2.25 0 002.25 7.5v9a2.25 2.25 0 002.25 2.25z" />
                        </svg>
                    </div>
                    <span class="mt-3 text-2xl font-bold text-white tracking-tight">VideoConnect</span>
                </a>
            </div>

            <div class="w-full sm:max-w-md px-8 py-10 bg-white shadow-2xl overflow-hidden sm:rounded-2xl relative">
                <!-- Decorative top strip -->
                <div class="absolute top-0 left-0 w-full h-1.5 bg-gradient-to-r from-indigo-500 via-purple-500 to-pink-500"></div>
                
                {{ $slot }}
            </div>
            
            <p class="mt-8 text-center text-sm text-gray-500">
                &copy; {{ date('Y') }} VideoConnect. <a href="#" class="hover:text-indigo-400">Privacy</a> &bull; <a href="#" class="hover:text-indigo-400">Terms</a>
            </p>
        </div>
    </body>
</html>
