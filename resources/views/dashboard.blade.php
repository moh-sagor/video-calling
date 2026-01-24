<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg h-[80vh] flex">
                <!-- Sidebar: User List & Meeting Controls -->
                <div class="w-1/3 border-r border-gray-200 flex flex-col">
                    <div class="p-4 border-b border-gray-200 bg-gray-50 space-y-4">
                        <h3 class="text-lg font-bold text-gray-700">Video Call App</h3>
                        
                        <!-- Meeting Controls -->
                        <div class="flex flex-col space-y-2">
                             <form action="{{ route('meet.create') }}" method="POST">
                                @csrf
                                <button type="submit" class="w-full bg-indigo-600 text-white py-2 rounded-lg hover:bg-indigo-700 transition font-medium">
                                    + New Meeting
                                </button>
                            </form>
                            
                            <div class="flex space-x-2">
                                <input type="text" id="meetingCodeInput" placeholder="Enter code" class="flex-1 border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                <button onclick="joinMeeting()" class="bg-gray-200 text-gray-700 px-3 py-2 rounded-lg hover:bg-gray-300 transition font-medium">Join</button>
                            </div>
                        </div>

                        <div class="text-xs font-semibold text-gray-500 uppercase tracking-wider mt-4">Online Users</div>
                    </div>
                <div class="flex-1 overflow-y-auto p-4 space-y-3">
                        @forelse($users as $user)
                            <div class="flex items-center justify-between bg-white p-3 rounded-lg shadow-sm border border-gray-100 hover:shadow-md transition">
                                <div class="flex items-center space-x-3">
                                    <div class="h-10 w-10 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-600 font-bold">
                                        {{ substr($user->name, 0, 1) }}
                                    </div>
                                    <span class="font-medium text-gray-800">{{ $user->name }}</span>
                                </div>
                                <button onclick="startCall({{ $user->id }})" class="bg-indigo-600 text-white p-2 rounded-full hover:bg-indigo-700 transition focus:outline-none" title="Start Video Call">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5l4.72-4.72a.75.75 0 011.28.53v11.38a.75.75 0 01-1.28.53l-4.72-4.72M4.5 18.75h9a2.25 2.25 0 002.25-2.25v-9a2.25 2.25 0 00-2.25-2.25h-9A2.25 2.25 0 002.25 7.5v9a2.25 2.25 0 002.25 2.25z" />
                                    </svg>
                                </button>
                            </div>
                        @empty
                            <p class="text-gray-500 text-center mt-4">No other users online.</p>
                        @endforelse
                    </div>
                </div>

                <!-- Main Area: Video Call -->
                <div class="w-2/3 relative bg-gray-900 flex flex-col justify-center items-center">
                    <!-- Placeholder State -->
                    <div id="videoPlaceholder" class="text-gray-400 text-center">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-24 h-24 mx-auto mb-4 opacity-50">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5l4.72-4.72a.75.75 0 011.28.53v11.38a.75.75 0 01-1.28.53l-4.72-4.72M4.5 18.75h9a2.25 2.25 0 002.25-2.25v-9a2.25 2.25 0 00-2.25-2.25h-9A2.25 2.25 0 002.25 7.5v9a2.25 2.25 0 002.25 2.25z" />
                        </svg>
                        <p class="text-xl">Select a user to start a video call</p>
                    </div>

                    <!-- Remote Video (Full Size) -->
                    <video id="remoteVideo" autoplay playsinline class="absolute inset-0 w-full h-full object-cover hidden"></video>

                    <!-- Local Video (Picture-in-Picture) -->
                    <video id="localVideo" autoplay playsinline muted class="absolute bottom-6 right-6 w-48 h-36 object-cover rounded-lg border-2 border-white shadow-lg hidden"></video>

                    <!-- Call Controls (Bottom Center) -->
                    <div id="callControl" class="absolute bottom-8 left-1/2 transform -translate-x-1/2 flex space-x-4 hidden z-10">
                        <button onclick="toggleAudio()" class="bg-gray-600 hover:bg-gray-700 text-white p-4 rounded-full shadow-lg transition">
                             <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.114 5.636a9 9 0 010 12.728M16.463 8.288a5.25 5.25 0 010 7.424M6.75 8.25l4.72-4.72a.75.75 0 011.28.53v15.88a.75.75 0 01-1.28.53l-4.72-4.72H4.51c-.88 0-1.704-.507-1.938-1.354A9.01 9.01 0 012.25 12c0-.83.112-1.633.322-2.396C2.806 8.756 3.63 8.25 4.51 8.25H6.75z" />
                            </svg>
                        </button>
                        <button onclick="endCall()" class="bg-red-600 hover:bg-red-700 text-white p-4 rounded-full shadow-lg transition">
                           <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-8 h-8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" /> <!-- Wrong icon for end call, let me use X or Phone Down -->
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                         <button onclick="toggleVideo()" class="bg-gray-600 hover:bg-gray-700 text-white p-4 rounded-full shadow-lg transition">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5l4.72-4.72a.75.75 0 011.28.53v11.38a.75.75 0 01-1.28.53l-4.72-4.72M4.5 18.75h9a2.25 2.25 0 002.25-2.25v-9a2.25 2.25 0 00-2.25-2.25h-9A2.25 2.25 0 002.25 7.5v9a2.25 2.25 0 002.25 2.25z" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Incoming Call Modal -->
            <div id="incomingCallModal" class="fixed inset-0 z-50 bg-gray-900 bg-opacity-75 flex items-center justify-center hidden">
                <div class="bg-white rounded-xl shadow-2xl p-8 max-w-sm w-full text-center transform transition-all scale-100">
                    <div class="mb-6">
                        <div class="mx-auto h-20 w-20 rounded-full bg-indigo-100 flex items-center justify-center animate-pulse">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-10 h-10 text-indigo-600">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5l4.72-4.72a.75.75 0 011.28.53v11.38a.75.75 0 01-1.28.53l-4.72-4.72M4.5 18.75h9a2.25 2.25 0 002.25-2.25v-9a2.25 2.25 0 00-2.25-2.25h-9A2.25 2.25 0 002.25 7.5v9a2.25 2.25 0 002.25 2.25z" />
                            </svg>
                        </div>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-2">Incoming Call</h3>
                    <p class="text-gray-600 mb-8"><span id="callerName" class="font-semibold text-indigo-600">Unknown</span> is calling you...</p>
                    <div class="flex justify-center space-x-6">
                         <button id="rejectCallBtn" class="flex flex-col items-center group">
                            <div class="w-14 h-14 bg-red-100 rounded-full flex items-center justify-center group-hover:bg-red-200 transition mb-1">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-red-600">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </div>
                            <span class="text-sm text-gray-500 font-medium group-hover:text-red-600">Decline</span>
                        </button>
                        <button id="acceptCallBtn" class="flex flex-col items-center group">
                            <div class="w-14 h-14 bg-green-100 rounded-full flex items-center justify-center group-hover:bg-green-200 transition mb-1">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-green-600">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.114 5.636a9 9 0 010 12.728M16.463 8.288a5.25 5.25 0 010 7.424M6.75 8.25l4.72-4.72a.75.75 0 011.28.53v15.88a.75.75 0 01-1.28.53l-4.72-4.72H4.51c-.88 0-1.704-.507-1.938-1.354A9.01 9.01 0 012.25 12c0-.83.112-1.633.322-2.396C2.806 8.756 3.63 8.25 4.51 8.25H6.75z" />
                                </svg>
                            </div>
                            <span class="text-sm text-gray-500 font-medium group-hover:text-green-600">Accept</span>
                        </button>
                    </div>
                </div>
            </div>
            
            <script>
                const authUserId = {{ auth()->id() }};
                
                function joinMeeting() {
                    const code = document.getElementById('meetingCodeInput').value;
                    if(code) {
                        window.location.href = '/meet/' + code;
                    } else {
                        alert('Please enter a meeting code');
                    }
                }
            </script>
        </div>
    </div>
</x-app-layout>
