<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'VideoConnect') }}</title>

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="h-full antialiased font-sans text-gray-900 bg-gray-50">
        
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 px-4 relative overflow-hidden">
            <!-- Subtle Background Gradients -->
            <div class="absolute top-0 right-0 -mr-20 -mt-20 w-96 h-96 rounded-full bg-indigo-100 blur-3xl opacity-50 -z-10"></div>
            <div class="absolute bottom-0 left-0 -ml-20 -mb-20 w-96 h-96 rounded-full bg-violet-100 blur-3xl opacity-50 -z-10"></div>

            <div class="mb-8">
                <a href="/" class="flex flex-col items-center group">
                    <div class="bg-indigo-600 p-3 rounded-2xl shadow-xl shadow-indigo-200 group-hover:scale-105 transition duration-300">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-8 h-8 text-white">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5l4.72-4.72a.75.75 0 011.28.53v11.38a.75.75 0 01-1.28.53l-4.72-4.72M4.5 18.75h9a2.25 2.25 0 002.25-2.25v-9a2.25 2.25 0 00-2.25-2.25h-9A2.25 2.25 0 002.25 7.5v9a2.25 2.25 0 002.25 2.25z" />
                        </svg>
                    </div>
                    <span class="mt-4 text-2xl font-bold text-gray-900 tracking-tight">VideoConnect</span>
                </a>
            </div>

            <div class="w-full sm:max-w-md px-8 py-10 bg-white shadow-xl shadow-gray-200/50 overflow-hidden sm:rounded-3xl border border-gray-100 relative">
                {{ $slot }}
            </div>
            
            <p class="mt-8 text-center text-sm text-gray-400">
                &copy; {{ date('Y') }} VideoConnect.
            </p>
        </div>
    </body>
</html>
