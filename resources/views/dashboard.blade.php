<x-app-layout>
    <div class="h-[calc(100vh-65px)] flex overflow-hidden bg-gray-50">
        
        <!-- Sidebar (User List) -->
        <!-- Hidden on mobile when video call is active, controlled by JS in a real app, 
             but here we'll use responsive classes to show it usually, or toggle it.
             For simpler implementation now: Collapsible on mobile or stacked. -->
        <aside class="w-full md:w-80 lg:w-96 bg-white border-r border-gray-200 flex flex-col z-20 md:relative absolute inset-y-0 transform -translate-x-full md:translate-x-0 transition duration-200 ease-in-out" id="sidebar">
            <!-- Sidebar Header -->
            <div class="p-6 border-b border-gray-100 bg-white">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-2xl font-bold text-gray-800 tracking-tight">Connect</h2>
                    <span class="px-2 py-1 bg-green-100 text-green-700 text-xs font-bold uppercase rounded-full tracking-wider">Online</span>
                </div>

                <!-- Join Meeting Controls -->
                <div class="space-y-3">
                    <form action="{{ route('meet.create') }}" method="POST">
                        @csrf
                        <button type="submit" class="w-full flex items-center justify-center space-x-2 bg-indigo-600 hover:bg-indigo-700 text-white py-3 px-4 rounded-xl transition shadow-lg shadow-indigo-100 font-medium">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                            </svg>
                            <span>New Meeting</span>
                        </button>
                    </form>
                    
                    <div class="relative">
                        <input type="text" id="meetingCodeInput" placeholder="Enter meeting code" class="w-full bg-gray-50 border border-gray-200 text-gray-900 text-sm rounded-xl focus:ring-indigo-500 focus:border-indigo-500 block p-3 pr-20 transition" />
                        <button onclick="joinMeeting()" class="absolute right-1 top-1 bottom-1 bg-white text-gray-600 hover:text-indigo-600 hover:bg-gray-50 border border-gray-100 px-3 rounded-lg text-sm font-semibold transition">
                            Join
                        </button>
                    </div>
                </div>
            </div>

            <!-- User List -->
            <div class="flex-1 overflow-y-auto px-4 py-4 space-y-2 custom-scrollbar">
                <div class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2 px-2">Users Online</div>
                
                @forelse($users as $user)
                    <div class="group flex items-center justify-between p-3 rounded-xl hover:bg-indigo-50 border border-transparent hover:border-indigo-100 transition cursor-pointer">
                        <div class="flex items-center space-x-3">
                            <div class="relative">
                                <div class="h-10 w-10 rounded-full bg-gradient-to-tr from-indigo-500 to-violet-500 flex items-center justify-center text-white font-bold shadow-sm">
                                    {{ substr($user->name, 0, 1) }}
                                </div>
                                <span class="absolute bottom-0 right-0 block h-2.5 w-2.5 rounded-full ring-2 ring-white bg-green-400"></span>
                            </div>
                            <div>
                                <h3 class="text-sm font-semibold text-gray-900 group-hover:text-indigo-700">{{ $user->name }}</h3>
                                <p class="text-xs text-gray-500 truncate w-32">Available for call</p>
                            </div>
                        </div>
                        <button onclick="startCall({{ $user->id }})" class="p-2 text-gray-400 hover:text-white hover:bg-indigo-600 rounded-full transition" title="Start Video Call">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5l4.72-4.72a.75.75 0 011.28.53v11.38a.75.75 0 01-1.28.53l-4.72-4.72M4.5 18.75h9a2.25 2.25 0 002.25-2.25v-9a2.25 2.25 0 00-2.25-2.25h-9A2.25 2.25 0 002.25 7.5v9a2.25 2.25 0 002.25 2.25z" />
                            </svg>
                        </button>
                    </div>
                @empty
                    <div class="text-center py-10">
                        <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-3 text-gray-400">
                             <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-8 h-8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 003.741-.479 3 3 0 00-4.682-2.72m.94 3.198l.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0112 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 016 18.719m12 0a5.971 5.971 0 00-.941-3.197m0 0A5.995 5.995 0 0012 12.75a5.995 5.995 0 00-5.058 2.772m0 0a3 3 0 00-4.681 2.72 8.986 8.986 0 003.74.477m.94-3.197a5.971 5.971 0 00-.94 3.197M15 6.75a3 3 0 11-6 0 3 3 0 016 0zm6 3a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0zm-13.5 0a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z" />
                            </svg>
                        </div>
                        <p class="text-gray-500 text-sm font-medium">No other users online.</p>
                        <p class="text-xs text-gray-400 mt-1">Wait for someone to join...</p>
                    </div>
                @endforelse
            </div>
            
            <!-- User Profile Snippet (Bottom) -->
            <div class="p-4 border-t border-gray-100 bg-gray-50">
                 <div class="flex items-center space-x-3">
                    <div class="h-9 w-9 rounded-full bg-gray-800 text-white flex items-center justify-center font-bold text-sm">
                        {{ substr(auth()->user()->name, 0, 1) }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-gray-900 truncate">{{ auth()->user()->name }}</p>
                        <p class="text-xs text-gray-500 truncate">{{ auth()->user()->email }}</p>
                    </div>
                 </div>
            </div>
        </aside>
        
        <!-- Mobile Sidebar Overlay -->
        <div id="sidebarOverlay" onclick="toggleSidebar()" class="fixed inset-0 bg-gray-900/50 z-10 md:hidden hidden backdrop-blur-sm transition-opacity"></div>

        <!-- Main Video Area -->
        <main class="flex-1 relative flex flex-col bg-gray-900 overflow-hidden">
            <!-- Mobile Toggle -->
            <button onclick="toggleSidebar()" class="md:hidden absolute top-4 left-4 z-30 p-2 bg-gray-800 text-white rounded-lg shadow-lg">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                </svg>
            </button>

            <!-- Video Placeholder Content -->
            <div id="videoPlaceholder" class="flex-1 flex flex-col items-center justify-center text-center p-8 bg-[url('https://images.unsplash.com/photo-1616593871249-1d90048D7e39?q=80&w=2832&auto=format&fit=crop')] bg-cover bg-center">
                 <div class="absolute inset-0 bg-gray-900/80 backdrop-blur-sm"></div>
                 
                 <div class="relative z-10 max-w-lg">
                    <div class="w-24 h-24 bg-white/10 rounded-3xl backdrop-blur flex items-center justify-center mx-auto mb-8 border border-white/10 shadow-2xl">
                         <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1" stroke="currentColor" class="w-12 h-12 text-indigo-400">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5l4.72-4.72a.75.75 0 011.28.53v11.38a.75.75 0 01-1.28.53l-4.72-4.72M4.5 18.75h9a2.25 2.25 0 002.25-2.25v-9a2.25 2.25 0 00-2.25-2.25h-9A2.25 2.25 0 002.25 7.5v9a2.25 2.25 0 002.25 2.25z" />
                        </svg>
                    </div>
                    <h2 class="text-3xl font-bold text-white mb-4">Start a Conversation</h2>
                    <p class="text-gray-400 text-lg mb-8">Select a user from the sidebar to start a video call, or create a new meeting to invite others.</p>
                 </div>
            </div>
            
            <!-- Active Call Interface -->
            <div id="videoContainer" class="hidden absolute inset-0 z-10">
                <!-- Remote Video -->
                <video id="remoteVideo" autoplay playsinline class="w-full h-full object-cover"></video>
                
                <!-- Overlay Gradient for Controls -->
                <div class="absolute inset-x-0 bottom-0 h-32 bg-gradient-to-t from-gray-900/90 to-transparent pointer-events-none"></div>

                <!-- Call Status / Timer (Top) -->
               <div class="absolute top-6 left-1/2 transform -translate-x-1/2 bg-gray-900/60 backdrop-blur-md px-4 py-1.5 rounded-full text-white text-sm font-medium border border-white/10 shadow-lg flex items-center space-x-2">
                    <span class="w-2 h-2 bg-red-500 rounded-full animate-pulse"></span>
                    <span>Live Call</span>
               </div>
            </div>

            <!-- Local Video (PiP) -->
            <div id="localVideoContainer" class="hidden absolute top-6 right-6 z-20 w-32 sm:w-48 aspect-video bg-gray-800 rounded-xl overflow-hidden border-2 border-white/20 shadow-2xl transition-all hover:scale-105 cursor-pointer">
                 <video id="localVideo" autoplay playsinline muted class="w-full h-full object-cover"></video>
                 <div class="absolute bottom-2 left-2 text-[10px] uppercase font-bold text-white/50 tracking-wider">You</div>
            </div>

            <!-- Call Controls (Floating) -->
            <div id="callControl" class="hidden absolute bottom-8 left-1/2 transform -translate-x-1/2 z-30 flex items-center space-x-4">
                 <button onclick="toggleAudio()" class="p-4 bg-gray-800/80 hover:bg-gray-700 backdrop-blur-md text-white rounded-full shadow-lg border border-white/10 transition group">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 group-active:scale-95 transition">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.114 5.636a9 9 0 010 12.728M16.463 8.288a5.25 5.25 0 010 7.424M6.75 8.25l4.72-4.72a.75.75 0 011.28.53v15.88a.75.75 0 01-1.28.53l-4.72-4.72H4.51c-.88 0-1.704-.507-1.938-1.354A9.01 9.01 0 012.25 12c0-.83.112-1.633.322-2.396C2.806 8.756 3.63 8.25 4.51 8.25H6.75z" />
                    </svg>
                </button>
                
                <button onclick="endCall()" class="p-5 bg-red-600 hover:bg-red-700 text-white rounded-full shadow-xl shadow-red-600/30 transition transform hover:-translate-y-1 active:scale-95">
                     <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-8 h-8">
                         <!-- Phone Down Icon -->
                         <path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" /> 
                     </svg>
                </button>

                <button onclick="toggleVideo()" class="p-4 bg-gray-800/80 hover:bg-gray-700 backdrop-blur-md text-white rounded-full shadow-lg border border-white/10 transition group">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 group-active:scale-95 transition">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5l4.72-4.72a.75.75 0 011.28.53v11.38a.75.75 0 01-1.28.53l-4.72-4.72M4.5 18.75h9a2.25 2.25 0 002.25-2.25v-9a2.25 2.25 0 00-2.25-2.25h-9A2.25 2.25 0 002.25 7.5v9a2.25 2.25 0 002.25 2.25z" />
                    </svg>
                </button>
            </div>

        </main>
    </div>

    <!-- Incoming Call Modal -->
    <div id="incomingCallModal" class="fixed inset-0 z-50 bg-gray-900/90 backdrop-blur-sm flex items-center justify-center hidden opacity-0 transition-opacity duration-300">
        <div class="bg-white rounded-2xl shadow-2xl p-8 max-w-sm w-full text-center transform scale-90 transition-transform duration-300">
            <div class="mb-6 relative">
                 <div class="absolute inset-0 bg-indigo-100 rounded-full animate-ping opacity-75"></div>
                <div class="relative mx-auto h-24 w-24 rounded-full bg-indigo-100 flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-12 h-12 text-indigo-600">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5l4.72-4.72a.75.75 0 011.28.53v11.38a.75.75 0 01-1.28.53l-4.72-4.72M4.5 18.75h9a2.25 2.25 0 002.25-2.25v-9a2.25 2.25 0 00-2.25-2.25h-9A2.25 2.25 0 002.25 7.5v9a2.25 2.25 0 002.25 2.25z" />
                    </svg>
                </div>
            </div>
            <h3 class="text-2xl font-bold text-gray-900 mb-1">Incoming Call</h3>
            <p class="text-gray-500 mb-8 font-medium">from <span id="callerName" class="text-indigo-600">Unknown</span></p>
            
            <div class="flex justify-center gap-8">
                 <button id="rejectCallBtn" class="flex flex-col items-center group transition">
                    <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center group-hover:bg-red-500 group-hover:text-white transition duration-200">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-7 h-7 text-red-600 group-hover:text-white">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </div>
                    <span class="text-sm text-gray-500 font-medium mt-2 group-hover:text-gray-700">Decline</span>
                </button>
                
                <button id="acceptCallBtn" class="flex flex-col items-center group transition">
                    <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center group-hover:bg-green-500 group-hover:text-white transition duration-200 shadow-green-200 hover:shadow-lg">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-7 h-7 text-green-600 group-hover:text-white">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.114 5.636a9 9 0 010 12.728M16.463 8.288a5.25 5.25 0 010 7.424M6.75 8.25l4.72-4.72a.75.75 0 011.28.53v15.88a.75.75 0 01-1.28.53l-4.72-4.72H4.51c-.88 0-1.704-.507-1.938-1.354A9.01 9.01 0 012.25 12c0-.83.112-1.633.322-2.396C2.806 8.756 3.63 8.25 4.51 8.25H6.75z" />
                        </svg>
                    </div>
                     <span class="text-sm text-gray-500 font-medium mt-2 group-hover:text-gray-700">Accept</span>
                </button>
            </div>
        </div>
    </div>
    
    <script>
        const authUserId = {{ auth()->id() }};
        
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebarOverlay');
            
            if (sidebar.classList.contains('-translate-x-full')) {
                // Open
                sidebar.classList.remove('-translate-x-full');
                overlay.classList.remove('hidden');
            } else {
                // Close
                sidebar.classList.add('-translate-x-full');
                overlay.classList.add('hidden');
            }
        }

        function joinMeeting() {
            const code = document.getElementById('meetingCodeInput').value;
            if(code) {
                window.location.href = '/meet/' + code;
            } else {
                alert('Please enter a meeting code'); // Could use a nicer toast here
            }
        }

        // Add some basic UI state management for video call start
        function updateUIForCallStart() {
            document.getElementById('videoPlaceholder').classList.add('hidden');
            document.getElementById('videoContainer').classList.remove('hidden');
            document.getElementById('localVideoContainer').classList.remove('hidden');
            document.getElementById('callControl').classList.remove('hidden');
            document.getElementById('callControl').classList.add('flex');
            
            // On mobile, close sidebar automatically
            if(window.innerWidth < 768) {
                const sidebar = document.getElementById('sidebar');
                if(!sidebar.classList.contains('-translate-x-full')) {
                    toggleSidebar();
                }
            }
        }
        
       function updateUIForCallEnd() {
            document.getElementById('videoPlaceholder').classList.remove('hidden');
            document.getElementById('videoContainer').classList.add('hidden');
            document.getElementById('localVideoContainer').classList.add('hidden');
            document.getElementById('callControl').classList.add('hidden');
            document.getElementById('callControl').classList.remove('flex');
       }
       
       // Note: The existing 'startCall' and other WebRTC functions in app.js/meeting.js 
       // should ideally hook into these UI update functions. 
       // For now, ensure your JS assigns the streams to #remoteVideo and #localVideo correctly.
    </script>
</x-app-layout>
