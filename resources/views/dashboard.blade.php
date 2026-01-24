<x-app-layout>
    <div class="h-[calc(100vh-65px)] flex overflow-hidden bg-gray-50 min-w-[1280px]">
        
        <!-- Sidebar (User List) -->
        <aside class="w-96 bg-white border-r border-gray-100 flex flex-col z-20 shadow-sm" id="sidebar">
            <div class="p-6 border-b border-gray-100">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-xl font-bold text-gray-900">Connections</h2>
                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800">
                        <span class="w-1.5 h-1.5 bg-green-500 rounded-full mr-1.5"></span>
                        Active
                    </span>
                </div>

                <!-- Controls -->
                <div class="space-y-4">
                    <form action="{{ route('meet.create') }}" method="POST">
                        @csrf
                        <button type="submit" class="w-full btn-primary py-3 rounded-lg shadow-md flex items-center justify-center space-x-2">
                             <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                            </svg>
                            <span>New Meeting</span>
                        </button>
                    </form>
                    
                    <div class="relative">
                        <input type="text" id="meetingCodeInput" placeholder="Enter code" class="input-modern bg-gray-50 border-gray-200" />
                        <button onclick="joinMeeting()" class="absolute right-2 top-2 bottom-2 text-indigo-600 hover:bg-indigo-50 px-3 rounded text-sm font-semibold transition">
                            Join
                        </button>
                    </div>
                    
                    <!-- Search User -->
                    <div class="pt-4 border-t border-gray-100">
                        <label class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2 block">Direct Connect</label>
                        <div class="relative">
                            <input type="email" id="emailSearchInput" placeholder="User email..." class="input-modern bg-gray-50 border-gray-200 text-xs" />
                            <button id="btnSearchUser" class="absolute right-2 top-2 bottom-2 text-indigo-600 hover:bg-indigo-50 px-3 rounded text-sm font-semibold transition">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
                                  <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                                </svg>
                            </button>
                        </div>
                        <p id="searchError" class="text-xs text-red-500 mt-1 hidden"></p>
                    </div>
                </div>
            </div>

            <!-- List -->
            <div class="flex-1 overflow-y-auto px-4 py-4 space-y-2" id="onlineUsersList">
                <div class="text-center py-8">
                    <p class="text-sm text-gray-400">Loading online users...</p>
                </div>
            </div>
            
            <div class="p-4 border-t border-gray-100 bg-gray-50">
                 <div class="flex items-center space-x-3">
                    <div class="h-9 w-9 rounded-full bg-gray-900 text-white flex items-center justify-center font-bold text-sm">
                        {{ substr(auth()->user()->name, 0, 1) }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-gray-900 truncate">{{ auth()->user()->name }}</p>
                        <p class="text-xs text-gray-500 truncate">{{ auth()->user()->email }}</p>
                    </div>
                 </div>
            </div>
        </aside>

        <!-- Main Area -->
        <main class="flex-1 relative flex flex-col bg-white overflow-hidden">
            <!-- Placeholder -->
            <div id="videoPlaceholder" class="flex-1 flex flex-col items-center justify-center text-center p-8 bg-gray-50/50">
                 <div class="w-24 h-24 bg-indigo-50 rounded-full flex items-center justify-center mb-6 text-indigo-600">
                     <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-10 h-10">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5l4.72-4.72a.75.75 0 011.28.53v11.38a.75.75 0 01-1.28.53l-4.72-4.72M4.5 18.75h9a2.25 2.25 0 002.25-2.25v-9a2.25 2.25 0 00-2.25-2.25h-9A2.25 2.25 0 002.25 7.5v9a2.25 2.25 0 002.25 2.25z" />
                    </svg>
                 </div>
                <h2 class="text-2xl font-bold text-gray-900 mb-2">Ready to connect?</h2>
                <p class="text-gray-500 max-w-sm">Select a user from the sidebar to chat or start a call.</p>
            </div>
            
            <!-- Video/Chat Interface -->
            <div id="videoContainer" class="hidden absolute inset-0 z-10 bg-black">
                <video id="remoteVideo" autoplay playsinline class="w-full h-full object-contain"></video>
                <div class="absolute inset-x-0 bottom-0 h-32 bg-gradient-to-t from-black/80 to-transparent pointer-events-none"></div>
                <div class="absolute top-6 left-1/2 transform -translate-x-1/2 bg-gray-900/80 backdrop-blur px-4 py-1.5 rounded-full text-white text-sm font-medium border border-white/10 flex items-center space-x-2 shadow-lg">
                    <span class="w-2 h-2 bg-red-500 rounded-full animate-pulse"></span>
                    <span>Live Call</span>
               </div>
            </div>

            <!-- Chat Window (Overlay or Integrated) -->
            <div id="chatWindow" class="absolute right-0 bottom-0 top-0 w-96 bg-white border-l border-gray-200 z-30 shadow-xl hidden flex flex-col">
                <div class="p-4 border-b border-gray-200 flex justify-between items-center bg-gray-50">
                    <h3 class="font-bold text-gray-800" id="chatTitle">Chat</h3>
                    <button onclick="document.getElementById('chatWindow').classList.add('hidden')" class="text-gray-500 hover:text-gray-700">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                    </button>
                </div>
                <div id="chatMessages" class="flex-1 overflow-y-auto p-4 space-y-3 bg-white">
                    <!-- Messages will be injected here -->
                </div>
                <div class="p-4 border-t border-gray-200 bg-gray-50">
                    <div class="flex space-x-2">
                        <input type="text" id="chatInput" placeholder="Type a message..." class="flex-1 rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 text-sm" onkeypress="if(event.key === 'Enter') window.sendMessage()">
                        <button onclick="window.sendMessage()" class="bg-indigo-600 text-white rounded-lg px-4 py-2 hover:bg-indigo-700 transition">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" /></svg>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Local Video -->
            <div id="localVideoContainer" class="hidden absolute top-6 right-6 z-20 w-64 aspect-video bg-gray-900 rounded-xl overflow-hidden border-2 border-white/10 shadow-2xl">
                 <video id="localVideo" autoplay playsinline muted class="w-full h-full object-cover"></video>
            </div>

            <!-- Controls -->
            <div id="callControl" class="hidden absolute bottom-8 left-1/2 transform -translate-x-1/2 z-30 flex items-center space-x-5">
                 <button onclick="toggleAudio()" class="p-4 bg-gray-800 hover:bg-gray-700 text-white rounded-full shadow-lg transition">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.114 5.636a9 9 0 010 12.728M16.463 8.288a5.25 5.25 0 010 7.424M6.75 8.25l4.72-4.72a.75.75 0 011.28.53v15.88a.75.75 0 01-1.28.53l-4.72-4.72H4.51c-.88 0-1.704-.507-1.938-1.354A9.01 9.01 0 012.25 12c0-.83.112-1.633.322-2.396C2.806 8.756 3.63 8.25 4.51 8.25H6.75z" />
                    </svg>
                </button>
                <button onclick="endCall()" class="p-4 bg-red-500 hover:bg-red-600 text-white rounded-full shadow-lg shadow-red-500/30 transition px-8">
                     <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-8 h-8">
                         <path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" /> 
                     </svg>
                </button>
                <button onclick="toggleVideo()" class="p-4 bg-gray-800 hover:bg-gray-700 text-white rounded-full shadow-lg transition">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5l4.72-4.72a.75.75 0 011.28.53v11.38a.75.75 0 01-1.28.53l-4.72-4.72M4.5 18.75h9a2.25 2.25 0 002.25-2.25v-9a2.25 2.25 0 00-2.25-2.25h-9A2.25 2.25 0 002.25 7.5v9a2.25 2.25 0 002.25 2.25z" />
                    </svg>
                </button>
            </div>

        </main>
    </div>

    <!-- Incoming Call Modal -->
    <div id="incomingCallModal" class="fixed inset-0 z-50 bg-gray-900/50 backdrop-blur-sm flex items-center justify-center hidden opacity-0 transition-opacity duration-300">
        <div class="bg-white rounded-2xl shadow-2xl p-8 max-w-sm w-full text-center transform scale-95 transition-transform duration-300">
            <div class="mb-6 relative">
                 <div class="absolute inset-0 bg-indigo-100 rounded-full animate-ping opacity-75"></div>
                <div class="relative mx-auto h-20 w-20 rounded-full bg-indigo-50 flex items-center justify-center">
                   <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-10 h-10 text-indigo-600">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5l4.72-4.72a.75.75 0 011.28.53v11.38a.75.75 0 01-1.28.53l-4.72-4.72M4.5 18.75h9a2.25 2.25 0 002.25-2.25v-9a2.25 2.25 0 00-2.25-2.25h-9A2.25 2.25 0 002.25 7.5v9a2.25 2.25 0 002.25 2.25z" />
                    </svg>
                </div>
            </div>
            <h3 class="text-xl font-bold text-gray-900 mb-1">Incoming Call</h3>
            <p class="text-gray-500 mb-8">from <span id="callerName" class="font-semibold text-gray-800">Unknown</span></p>
            <div class="flex justify-center gap-6">
                 <button id="rejectCallBtn" class="flex flex-col items-center group">
                    <div class="w-14 h-14 bg-red-100 rounded-full flex items-center justify-center group-hover:bg-red-500 group-hover:text-white transition duration-200">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-6 h-6 text-red-600 group-hover:text-white">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </div>
                </button>
                <button id="acceptCallBtn" class="flex flex-col items-center group">
                    <div class="w-14 h-14 bg-green-100 rounded-full flex items-center justify-center group-hover:bg-green-500 group-hover:text-white transition duration-200 shadow-green-200">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-6 h-6 text-green-600 group-hover:text-white">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.114 5.636a9 9 0 010 12.728M16.463 8.288a5.25 5.25 0 010 7.424M6.75 8.25l4.72-4.72a.75.75 0 011.28.53v15.88a.75.75 0 01-1.28.53l-4.72-4.72H4.51c-.88 0-1.704-.507-1.938-1.354A9.01 9.01 0 012.25 12c0-.83.112-1.633.322-2.396C2.806 8.756 3.63 8.25 4.51 8.25H6.75z" />
                        </svg>
                    </div>
                </button>
            </div>
        </div>
    </div>
    
    <script>
        const authUserId = {{ auth()->id() }};
        window.authUserId = authUserId; // Expose for chat.js
        
        function joinMeeting() {
            const code = document.getElementById('meetingCodeInput').value;
            if(code) window.location.href = '/meet/' + code;
            else alert('Please enter a valid code'); 
        }
        function updateUIForCallStart() {
            document.getElementById('videoPlaceholder').classList.add('hidden');
            document.getElementById('videoContainer').classList.remove('hidden');
            document.getElementById('localVideoContainer').classList.remove('hidden');
            document.getElementById('callControl').classList.remove('hidden');
            document.getElementById('callControl').classList.add('flex');
        }
       function updateUIForCallEnd() {
            document.getElementById('videoPlaceholder').classList.remove('hidden');
            document.getElementById('videoContainer').classList.add('hidden');
            document.getElementById('localVideoContainer').classList.add('hidden');
            document.getElementById('callControl').classList.add('hidden');
            document.getElementById('callControl').classList.remove('flex');
       }
    </script>
</x-app-layout>
