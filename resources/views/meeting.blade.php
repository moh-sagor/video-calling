<x-app-layout>
    <div class="h-screen py-6 flex flex-col items-center justify-center bg-gray-50" style="height: calc(100vh - 65px);">
        <div class="w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-full">
            <div class="bg-black rounded-2xl overflow-hidden h-full relative flex flex-col items-center justify-center shadow-2xl">
                
                <!-- Info Pill -->
                <div class="absolute top-6 left-6 z-10 bg-gray-900/80 backdrop-blur px-4 py-2 rounded-lg text-white border border-white/10 flex items-center space-x-3">
                    <div class="flex flex-col">
                        <span class="text-[10px] text-gray-400 uppercase tracking-wider font-semibold">Meeting Code</span>
                        <span class="font-bold text-lg tracking-wide">{{ $meeting->code }}</span>
                    </div>
                </div>

                <!-- Main Video Area -->
                <div class="relative w-full h-full">
                    <!-- Placeholder -->
                    <div id="waitingPlaceholder" class="absolute inset-0 flex flex-col items-center justify-center text-white">
                        <div class="w-20 h-20 bg-gray-800 rounded-full flex items-center justify-center mb-6">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-10 h-10 text-gray-400">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                            </svg>
                        </div>
                        <h3 class="text-2xl font-bold">Waiting for participants...</h3>
                        <p class="text-gray-400 mt-2">Share the code <span class="text-white font-mono">{{ $meeting->code }}</span> to invite others.</p>
                    </div>

                    <!-- Remote Video -->
                    <video id="remoteVideo" autoplay playsinline class="w-full h-full object-contain hidden"></video>

                    <!-- Local Video (PiP) -->
                    <video id="localVideo" autoplay playsinline muted class="absolute bottom-6 right-6 w-60 h-auto rounded-xl border-2 border-white/10 shadow-2xl bg-gray-900 object-cover aspect-video"></video>
                </div>

                <!-- Controls -->
                 <div class="absolute bottom-8 flex space-x-4 z-20">
                    <button onclick="toggleAudio()" id="btnAudio" class="bg-gray-800 hover:bg-gray-700 text-white p-4 rounded-full shadow-lg transition">
                         <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 01-3-3V5a3 3 0 116 0v6a3 3 0 01-3 3z" />
                        </svg>
                    </button>
                    <button onclick="leaveMeeting()" class="bg-red-500 hover:bg-red-600 text-white p-4 rounded-full shadow-lg shadow-red-500/30 transition px-8">
                       <span class="font-bold text-sm uppercase tracking-wide">Leave</span>
                    </button>
                     <button onclick="toggleVideo()" id="btnVideo" class="bg-gray-800 hover:bg-gray-700 text-white p-4 rounded-full shadow-lg transition">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                        </svg>
                    </button>
                </div>

                <script>
                    const MEETING_CODE = "{{ $meeting->code }}";
                    const AUTH_USER_ID = {{ auth()->id() }};
                </script>
            </div>
        </div>
    </div>
</x-app-layout>
