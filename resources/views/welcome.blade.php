<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=1280"> <!-- Fixed viewport for desktop -->
    <title>{{ config('app.name', 'VideoConnect') }} - Premium Video Conferencing</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="antialiased bg-white text-gray-900 font-sans min-w-[1280px]">

    <!-- Navigation -->
    <nav class="fixed w-full z-50 bg-white/80 backdrop-blur-md border-b border-gray-100 transition-all duration-300">
        <div class="max-w-7xl mx-auto px-8">
            <div class="flex justify-between h-24 items-center">
                <!-- Logo -->
                <div class="flex items-center">
                    <a href="/" class="flex items-center space-x-3">
                        <div class="bg-indigo-600 p-2.5 rounded-xl shadow-lg shadow-indigo-200">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-7 h-7 text-white">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5l4.72-4.72a.75.75 0 011.28.53v11.38a.75.75 0 01-1.28.53l-4.72-4.72M4.5 18.75h9a2.25 2.25 0 002.25-2.25v-9a2.25 2.25 0 00-2.25-2.25h-9A2.25 2.25 0 002.25 7.5v9a2.25 2.25 0 002.25 2.25z" />
                            </svg>
                        </div>
                        <span class="font-bold text-2xl tracking-tight text-gray-900">VideoConnect</span>
                    </a>
                </div>

                <!-- Desktop Menu (Always Visible) -->
                <div class="flex items-center space-x-12">
                    <a href="#features" class="text-base font-medium text-gray-600 hover:text-indigo-600 transition">Features</a>
                    <a href="#how-it-works" class="text-base font-medium text-gray-600 hover:text-indigo-600 transition">How it works</a>
                    <a href="#pricing" class="text-base font-medium text-gray-600 hover:text-indigo-600 transition">Pricing</a>
                </div>

                <!-- Auth Buttons -->
                <div class="flex items-center space-x-6">
                    @if (Route::has('login'))
                        @auth
                            <a href="{{ url('/dashboard') }}" class="text-base font-medium text-gray-700 hover:text-indigo-600 transition">Dashboard</a>
                        @else
                            <a href="{{ route('login') }}" class="text-base font-medium text-gray-600 hover:text-indigo-600 transition px-4">Log in</a>
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="bg-indigo-600 text-white px-8 py-3 rounded-full text-base font-medium hover:bg-indigo-700 transition shadow-lg shadow-indigo-100 hover:-translate-y-0.5">Get Started</a>
                            @endif
                        @endauth
                    @endif
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <div class="relative pt-48 pb-32 overflow-hidden bg-white">
        <!-- Background Blobs -->
        <div class="absolute top-0 left-1/2 -translate-x-1/2 w-[1400px] h-[600px] bg-gradient-to-r from-indigo-50 to-purple-50 rounded-full blur-3xl opacity-50 -z-10 mt-[-150px]"></div>

        <div class="max-w-7xl mx-auto px-8 text-center">
            <div class="inline-flex items-center px-4 py-1.5 rounded-full bg-indigo-50 border border-indigo-100 text-indigo-600 text-sm font-semibold uppercase tracking-wide mb-8">
                <span>New v2.0 Released</span>
            </div>
            <h1 class="text-8xl font-extrabold tracking-tight text-gray-900 mb-10 leading-tight">
                Video calling for <br>
                <span class="text-transparent bg-clip-text bg-gradient-to-r from-indigo-600 to-violet-600">everyone.</span>
            </h1>
            <p class="text-2xl text-gray-500 mb-12 max-w-3xl mx-auto leading-relaxed">
                Experience crystal clear video calls directly in your browser. No downloads, no plugins, just seamless connection.
            </p>
            <div class="flex flex-row items-center justify-center gap-6">
                @auth
                    <a href="{{ url('/dashboard') }}" class="px-10 py-5 bg-indigo-600 text-white rounded-full font-bold text-xl hover:bg-indigo-700 transition shadow-xl shadow-indigo-200 hover:-translate-y-1">
                        Go to Dashboard
                    </a>
                @else
                    <a href="{{ route('register') }}" class="px-10 py-5 bg-indigo-600 text-white rounded-full font-bold text-xl hover:bg-indigo-700 transition shadow-xl shadow-indigo-200 hover:-translate-y-1">
                        Start Free Meeting
                    </a>
                    <a href="#how-it-works" class="px-10 py-5 bg-white text-gray-700 border border-gray-200 rounded-full font-bold text-xl hover:bg-gray-50 transition hover:-translate-y-1 flex items-center justify-center gap-3">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-6 h-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.91 11.672a.375.375 0 010 .656l-5.603 3.113a.375.375 0 01-.557-.328V8.887c0-.286.307-.466.557-.327l5.603 3.112z" />
                        </svg>
                        See how it works
                    </a>
                @endauth
            </div>
        </div>

        <!-- Hero Image/Graphic -->
        <div class="mt-28 relative max-w-6xl mx-auto px-8">
            <div class="rounded-3xl bg-gray-900 p-3 shadow-2xl ring-1 ring-gray-900/10">
                <div class="relative bg-gray-800 aspect-video rounded-2xl overflow-hidden">
                    <!-- Fake UI -->
                    <div class="absolute inset-0 flex items-center justify-center">
                         <div class="text-center">
                            <div class="w-32 h-32 bg-gradient-to-br from-indigo-500 to-purple-500 rounded-full mx-auto mb-6 flex items-center justify-center text-white text-4xl font-bold border-4 border-gray-700">AB</div>
                            <p class="text-white font-medium text-2xl">Alex Brown</p>
                            <p class="text-indigo-400 text-lg animate-pulse mt-2">Speaking...</p>
                         </div>
                    </div>
                    <!-- Controls Bar -->
                    <div class="absolute bottom-10 left-1/2 -translate-x-1/2 bg-gray-900/90 backdrop-blur px-8 py-4 rounded-full flex gap-8 border border-white/10">
                        <div class="w-14 h-14 bg-gray-700 rounded-full flex items-center justify-center text-white cursor-pointer hover:bg-gray-600 transition"><svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 01-3-3V5a3 3 0 116 0v6a3 3 0 01-3 3z" /></svg></div>
                        <div class="w-14 h-14 bg-red-500 rounded-full flex items-center justify-center text-white shadow-lg shadow-red-500/30 cursor-pointer hover:bg-red-600 transition"><svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 8l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2M5 3a2 2 0 00-2 2v1c0 8.284 6.716 15 15 15h1a2 2 0 002-2v-3.28a1 1 0 00-.684-.948l-4.493-1.498a1 1 0 00-1.21.502l-1.13 2.257a11.042 11.042 0 01-5.516-5.517l2.257-1.128a1 1 0 00.502-1.21L9.228 3.683A1 1 0 008.279 3H5z" /></svg></div>
                        <div class="w-14 h-14 bg-gray-700 rounded-full flex items-center justify-center text-white cursor-pointer hover:bg-gray-600 transition"><svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" /></svg></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Trusted By -->
    <section class="py-16 bg-white border-b border-gray-100">
        <div class="max-w-7xl mx-auto px-8 text-center">
            <p class="text-base font-semibold text-gray-500 uppercase tracking-widest mb-10">Trusted by 10,000+ Teams</p>
            <div class="flex flex-row justify-center items-center gap-24 opacity-40 grayscale hover:grayscale-0 transition-all duration-500">
                 <!-- Simple text logos for placeholder -->
                 <span class="text-3xl font-black text-gray-800">Acme<span class="text-gray-400">Corp</span></span>
                 <span class="text-3xl font-serif font-bold text-gray-800 italic">Vortex</span>
                 <span class="text-2xl font-bold text-gray-800 border-2 border-gray-800 p-1.5">BLOCKS</span>
                 <span class="text-3xl font-extrabold text-gray-800 tracking-tight">Focus.</span>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section id="features" class="py-32 bg-gray-50">
        <div class="max-w-7xl mx-auto px-8">
            <div class="text-center max-w-4xl mx-auto mb-20">
                <span class="text-indigo-600 font-semibold tracking-wider uppercase text-sm">Features</span>
                <h2 class="mt-3 text-5xl font-extrabold text-gray-900">Everything you need for better meetings</h2>
            </div>

            <div class="grid grid-cols-3 gap-12">
                <!-- Feature 1 -->
                <div class="bg-white p-10 rounded-3xl shadow-sm border border-gray-100 hover:shadow-xl transition duration-300">
                    <div class="w-16 h-16 bg-blue-50 rounded-2xl flex items-center justify-center mb-8 text-blue-600">
                        <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" /></svg>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-4">HD Video</h3>
                    <p class="text-lg text-gray-500 leading-relaxed">Adaptive video quality up to 4k. Looks great on any screen.</p>
                </div>
                 <!-- Feature 2 -->
                <div class="bg-white p-10 rounded-3xl shadow-sm border border-gray-100 hover:shadow-xl transition duration-300">
                    <div class="w-16 h-16 bg-purple-50 rounded-2xl flex items-center justify-center mb-8 text-purple-600">
                        <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" /></svg>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-4">End-to-End Encrypted</h3>
                    <p class="text-lg text-gray-500 leading-relaxed">Your calls are private and secure. We can't see or hear your meetings.</p>
                </div>
                 <!-- Feature 3 -->
                <div class="bg-white p-10 rounded-3xl shadow-sm border border-gray-100 hover:shadow-xl transition duration-300">
                     <div class="w-16 h-16 bg-pink-50 rounded-2xl flex items-center justify-center mb-8 text-pink-600">
                        <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0z" /></svg>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-4">Unlimited Users</h3>
                    <p class="text-lg text-gray-500 leading-relaxed">Host meetings with as many people as you need, for free.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- How it Works -->
    <section id="how-it-works" class="py-32 bg-white">
        <div class="max-w-7xl mx-auto px-8">
            <div class="grid grid-cols-2 gap-24 items-center">
                <div>
                    <span class="text-indigo-600 font-semibold tracking-wider uppercase text-sm">Easy Workflow</span>
                    <h2 class="mt-3 text-4xl font-extrabold text-gray-900 mb-8">Start a meeting in 3 seconds</h2>
                    <p class="text-xl text-gray-500 mb-10 leading-relaxed">Stop fumbling with installers and invite codes. VideoConnect makes it as easy as sharing a link.</p>
                    
                    <ul class="space-y-10">
                        <li class="flex items-start">
                             <div class="flex-shrink-0 h-10 w-10 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-600 font-bold text-lg">1</div>
                             <div class="ml-6">
                                 <h4 class="text-xl font-bold text-gray-900">Create a Room</h4>
                                 <p class="text-base text-gray-500 mt-1">Click one button to generate a secure, unique meeting room.</p>
                             </div>
                        </li>
                        <li class="flex items-start">
                             <div class="flex-shrink-0 h-10 w-10 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-600 font-bold text-lg">2</div>
                             <div class="ml-6">
                                 <h4 class="text-xl font-bold text-gray-900">Share the Link</h4>
                                 <p class="text-base text-gray-500 mt-1">Send the URL to your team via Slack, Email, or SMS.</p>
                             </div>
                        </li>
                        <li class="flex items-start">
                             <div class="flex-shrink-0 h-10 w-10 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-600 font-bold text-lg">3</div>
                             <div class="ml-6">
                                 <h4 class="text-xl font-bold text-gray-900">Start Talking</h4>
                                 <p class="text-base text-gray-500 mt-1">Guests join instantly. No account required.</p>
                             </div>
                        </li>
                    </ul>
                </div>
                <div>
                    <div class="bg-gray-50 rounded-3xl p-8 border border-gray-100">
                        <img src="https://images.unsplash.com/photo-1531482615713-2afd69097998?ixlib=rb-1.2.1&auto=format&fit=crop&w=1350&q=80" alt="Team meeting" class="rounded-2xl shadow-xl w-full h-auto">
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA -->
    <section class="py-24 bg-indigo-600">
        <div class="max-w-5xl mx-auto px-8 text-center">
            <h2 class="text-4xl font-bold text-white mb-8">Ready to upgrade your meetings?</h2>
            <p class="text-indigo-100 text-2xl mb-12 max-w-3xl mx-auto">Join thousands of teams who rely on VideoConnect for their daily communication. It's fast, free, and secure.</p>
            @auth
                 <a href="{{ url('/dashboard') }}" class="inline-block bg-white text-indigo-600 px-12 py-5 rounded-full font-bold text-xl hover:bg-gray-50 transition shadow-2xl transform hover:-translate-y-1">Return to Dashboard</a>
            @else
                 <a href="{{ route('register') }}" class="inline-block bg-white text-indigo-600 px-12 py-5 rounded-full font-bold text-xl hover:bg-gray-50 transition shadow-2xl transform hover:-translate-y-1">Get Started for Free</a>
            @endauth
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-white border-t border-gray-100 pt-20 pb-12">
        <div class="max-w-7xl mx-auto px-8">
            <div class="flex flex-row justify-between items-center">
                <div>
                     <span class="font-bold text-2xl text-gray-900">VideoConnect</span>
                     <p class="text-base text-gray-500 mt-2">Making connection simple.</p>
                </div>
                <div class="flex space-x-10">
                    <a href="#" class="text-gray-400 hover:text-gray-600 transition text-base">Privacy</a>
                    <a href="#" class="text-gray-400 hover:text-gray-600 transition text-base">Terms</a>
                    <a href="#" class="text-gray-400 hover:text-gray-600 transition text-base">Twitter</a>
                    <a href="#" class="text-gray-400 hover:text-gray-600 transition text-base">Contact</a>
                </div>
            </div>
            <div class="mt-12 pt-12 border-t border-gray-100 text-center text-base text-gray-400">
                &copy; {{ date('Y') }} VideoConnect. All rights reserved.
            </div>
        </div>
    </footer>
</body>
</html>
