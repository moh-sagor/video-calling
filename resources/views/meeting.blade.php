<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-gray-900 overflow-hidden shadow-sm sm:rounded-lg h-[85vh] relative flex flex-col items-center justify-center">
                
                <!-- Header / Info -->
                <div class="absolute top-4 left-4 z-10 bg-black bg-opacity-50 px-4 py-2 rounded text-white">
                    <h2 class="font-bold">Meeting: <span class="tracking-widest text-indigo-400">{{ $meeting->code }}</span></h2>
                    <p class="text-xs text-gray-300">Share this code to invite others.</p>
                </div>

                <!-- Video Area -->
                <div class="relative w-full h-full">
                    <!-- Participant List -->
                    <div class="absolute top-4 right-4 z-20 bg-gray-800 bg-opacity-80 rounded p-4 text-white w-64 max-h-60 overflow-y-auto">
                        <h3 class="text-sm font-bold uppercase tracking-wider mb-2 text-gray-400">Participants</h3>
                        <ul id="participantList" class="space-y-2 text-sm">
                            <li class="text-gray-500 italic">No one else here...</li>
                        </ul>
                    </div>

                    <!-- Placeholder -->
                    <div id="waitingPlaceholder" class="absolute inset-0 flex flex-col items-center justify-center text-white">
                        <div class="animate-pulse mb-4">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-20 h-20 text-gray-400">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold">Waiting for others to join...</h3>
                        <p class="text-sm text-gray-400 mt-2">If connection fails, use the "Connect" button in the list.</p>
                    </div>

                    <!-- Remote Video -->
                    <video id="remoteVideo" autoplay playsinline class="w-full h-full object-cover hidden"></video>

                    <!-- Local Video (PiP) -->
                    <video id="localVideo" autoplay playsinline muted class="absolute bottom-6 right-6 w-56 h-40 object-cover rounded-lg border-2 border-white shadow-lg bg-black"></video>
                </div>

                <!-- Controls -->
                 <div class="absolute bottom-8 flex space-x-6 z-20">
                    <button onclick="toggleAudio()" id="btnAudio" class="bg-gray-700 hover:bg-gray-600 text-white p-4 rounded-full shadow-lg transition">
                         <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 01-3-3V5a3 3 0 116 0v6a3 3 0 01-3 3z" />
                        </svg>
                    </button>
                    <button onclick="leaveMeeting()" class="bg-red-600 hover:bg-red-700 text-white p-4 rounded-full shadow-lg transition px-8">
                       <span class="font-bold">Leave</span>
                    </button>
                     <button onclick="toggleVideo()" id="btnVideo" class="bg-gray-700 hover:bg-gray-600 text-white p-4 rounded-full shadow-lg transition">
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
