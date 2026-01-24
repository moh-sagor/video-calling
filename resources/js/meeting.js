const config = {
    iceServers: [
        { urls: 'stun:stun.l.google.com:19302' }
    ]
};

let localStream;
let remoteStream;
let peerConnection;
let channel;
let participants = [];

document.addEventListener('DOMContentLoaded', () => {
    if (typeof MEETING_CODE !== 'undefined') {
        initMeeting();
    }
    
    // Debug Connection
    const statusDiv = document.createElement('div');
    statusDiv.style.position = 'fixed';
    statusDiv.style.bottom = '10px';
    statusDiv.style.left = '10px';
    statusDiv.style.color = 'lime';
    statusDiv.style.zIndex = '100';
    statusDiv.style.background = 'rgba(0,0,0,0.7)';
    statusDiv.style.padding = '5px';
    statusDiv.id = 'connDebug';
    document.body.appendChild(statusDiv);

    window.Echo.connector.pusher.connection.bind('state_change', (states) => {
        statusDiv.innerText = `WS: ${states.current}`;
    });
    
    statusDiv.innerText = `WS: ${window.Echo.connector.pusher.connection.state}`;
});

async function initMeeting() {
    console.log('Initializing meeting: ' + MEETING_CODE);
    const localVideo = document.getElementById('localVideo');
    const remoteVideo = document.getElementById('remoteVideo');
    const waitingPlaceholder = document.getElementById('waitingPlaceholder');

    try {
        if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) {
             alert('WebRTC Camera access requires HTTPS! You are likely using HTTP. Please see RUNNING_HTTPS.md or use localhost.');
             throw new Error('Secure context required');
        }
        localStream = await navigator.mediaDevices.getUserMedia({ video: true, audio: true });
        localVideo.srcObject = localStream;
    } catch (e) {
        console.error('Error getting media:', e);
        if (e.name === 'NotReadableError') {
             alert('Camera/Mic is currently in use by another application or tab! Please close them and refresh.');
        } else if (e.name === 'NotAllowedError') {
             alert('Permission denied! Please allow camera access in your browser settings.');
        } else if (e.name === 'NotFoundError') {
             alert('No camera or microphone found on this device.');
        } else {
             alert(`Error accessing media: ${e.name} - ${e.message}`);
        }
        return;
    }

    channel = window.Echo.join(`meeting.${MEETING_CODE}`)
        .here((users) => {
            console.log('Users here:', users);
            participants = users.filter(u => u.id !== AUTH_USER_ID);
            updateParticipantList();
        })
        .joining((user) => {
            console.log('User joining:', user);
            participants.push(user);
            updateParticipantList();
            
            console.log('Initiating connection to ' + user.name);
            startConnection(user.id);
        })
        .leaving((user) => {
            console.log('User leaving:', user);
            participants = participants.filter(u => u.id !== user.id);
            updateParticipantList();
            
            if (peerConnection) {
                 closeConnection();
                 remoteVideo.classList.add('hidden');
                 waitingPlaceholder.classList.remove('hidden');
                 waitingPlaceholder.innerHTML = '<h3 class="text-xl font-semibold">User left. Waiting...</h3>';
            }
        })
        .listenForWhisper('offer', async (e) => {
            console.log('Received offer from ' + e.from);
            // Visual feedback
            waitingPlaceholder.innerHTML = '<h3 class="text-xl font-semibold">Connecting...</h3>';
            
            if (peerConnection) {
                 console.warn('Already have a connection, handling renegotiation or ignoring');
                 // return; // For simple 1-to-1, maybe we reset?
            }

            createPeerConnection(e.from);
            await peerConnection.setRemoteDescription(new RTCSessionDescription(e.offer));
            
            const answer = await peerConnection.createAnswer();
            await peerConnection.setLocalDescription(answer);

            console.log('Sending answer to ' + e.from);
            channel.whisper('answer', {
                from: AUTH_USER_ID,
                to: e.from,
                answer: answer
            });
        })
        .listenForWhisper('answer', async (e) => {
            console.log('Received answer from ' + e.from);
             if (peerConnection) {
                await peerConnection.setRemoteDescription(new RTCSessionDescription(e.answer));
             }
        })
        .listenForWhisper('ice-candidate', async (e) => {
            console.log('Received ICE candidate');
            if (peerConnection) {
                await peerConnection.addIceCandidate(new RTCIceCandidate(e.candidate));
            }
        });
}

function createPeerConnection(remoteUserId) {
    if (peerConnection) return;
    console.log('Creating RTCPeerConnection');

    peerConnection = new RTCPeerConnection(config);
    const remoteVideo = document.getElementById('remoteVideo');
    const waitingPlaceholder = document.getElementById('waitingPlaceholder');

    localStream.getTracks().forEach(track => {
        peerConnection.addTrack(track, localStream);
    });

    peerConnection.ontrack = (event) => {
        console.log('Received remote track');
        remoteVideo.srcObject = event.streams[0];
        remoteVideo.classList.remove('hidden');
        waitingPlaceholder.classList.add('hidden');
    };

    peerConnection.onicecandidate = (event) => {
        if (event.candidate) {
            channel.whisper('ice-candidate', {
                candidate: event.candidate,
                from: AUTH_USER_ID,
                to: remoteUserId
            });
        }
    };
    
    peerConnection.onconnectionstatechange = () => {
        console.log('Connection state:', peerConnection.connectionState);
    };
}

function updateParticipantList() {
    const list = document.getElementById('participantList');
    if (!list) return;
    
    list.innerHTML = '';

    if (participants.length === 0) {
        list.innerHTML = '<li class="text-gray-500 italic">No one else here...</li>';
        return;
    }

    participants.forEach(user => {
        const li = document.createElement('li');
        li.className = 'flex justify-between items-center bg-gray-700 p-2 rounded';
        li.innerHTML = `
            <span>${user.name}</span>
            <button onclick="startConnection(${user.id})" class="text-xs bg-green-600 hover:bg-green-500 text-white px-2 py-1 rounded ml-2">Connect</button>
        `;
        list.appendChild(li);
    });
}

// Exposed to global scope for button click
window.startConnection = async (targetUserId) => {
    console.log('Manual/Auto start connection flow to', targetUserId);
    
    // Reset if exists
    if (peerConnection) {
        peerConnection.close();
        peerConnection = null;
    }

    createPeerConnection(targetUserId);

    const offer = await peerConnection.createOffer();
    await peerConnection.setLocalDescription(offer);

    console.log('Sending offer to ' + targetUserId);
    channel.whisper('offer', {
        offer: offer,
        from: AUTH_USER_ID,
        to: targetUserId
    });
};



function closeConnection() {
    if (peerConnection) {
        peerConnection.close();
        peerConnection = null;
    }
}

window.leaveMeeting = () => {
    if (localStream) {
        localStream.getTracks().forEach(track => track.stop());
    }
    closeConnection();
    window.location.href = '/dashboard';
};

window.toggleAudio = () => {
     if (localStream) {
        const audioTrack = localStream.getAudioTracks()[0];
        if (audioTrack) {
            audioTrack.enabled = !audioTrack.enabled;
            document.getElementById('btnAudio').classList.toggle('bg-red-500');
            document.getElementById('btnAudio').classList.toggle('bg-gray-700');
        }
    }
};

window.toggleVideo = () => {
    if (localStream) {
        const videoTrack = localStream.getVideoTracks()[0];
        if (videoTrack) {
            videoTrack.enabled = !videoTrack.enabled;
             document.getElementById('btnVideo').classList.toggle('bg-red-500');
             document.getElementById('btnVideo').classList.toggle('bg-gray-700');
        }
    }
}
