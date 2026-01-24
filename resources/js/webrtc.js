let localStream;
let remoteStream;
let peerConnection;
const config = {
    iceServers: [
        {
            urls: 'stun:stun.l.google.com:19302' // Use public STUN for demo. Replace with self-hosted Coturn for production.
        }
    ]
};

let incomingCall = null;

    // DOM Elements
const localVideo = document.getElementById('localVideo');
const remoteVideo = document.getElementById('remoteVideo');
const incomingCallModal = document.getElementById('incomingCallModal');
const callerNameSpan = document.getElementById('callerName');
const acceptCallBtn = document.getElementById('acceptCallBtn');
const rejectCallBtn = document.getElementById('rejectCallBtn');
const callControl = document.getElementById('callControl');
const videoPlaceholder = document.getElementById('videoPlaceholder');

window.startCall = async (userId) => {
    console.log('Starting call to ' + userId);
    try {
        localStream = await navigator.mediaDevices.getUserMedia({ video: true, audio: true });
        localVideo.srcObject = localStream;
        localVideo.classList.remove('hidden');

        peerConnection = new RTCPeerConnection(config);
        
        // Add tracks
        localStream.getTracks().forEach(track => {
            peerConnection.addTrack(track, localStream);
        });

        // Handle remote stream
        peerConnection.ontrack = (event) => {
            console.log('Received remote track');
            remoteVideo.srcObject = event.streams[0];
            remoteStream = event.streams[0];
            
            // UI Updates
            remoteVideo.classList.remove('hidden');
            videoPlaceholder.classList.add('hidden');
            callControl.classList.remove('hidden');
        };

        // Handle ICE candidates
        peerConnection.onicecandidate = (event) => {
            if (event.candidate) {
                axios.post('/call/ice-candidate', {
                    to: userId,
                    candidate: event.candidate,
                    call_id: window.currentCallId 
                });
            }
        };

        // Create Offer
        const offer = await peerConnection.createOffer();
        await peerConnection.setLocalDescription(offer);

        // Send offer to server
        const response = await axios.post('/call/start', {
            user_to_call: userId,
            offer: offer
        });
        
        window.currentCallId = response.data.call_id;
        window.currentPeerId = userId; 

        console.log('Offer sent, call ID:', window.currentCallId);

    } catch (error) {
        console.error('Error starting call:', error);
        alert('Could not start call: ' + error.message);
    }
};

window.acceptCall = async () => {
    if (!incomingCall) return;
    
    incomingCallModal.classList.add('hidden');
    
    try {
        localStream = await navigator.mediaDevices.getUserMedia({ video: true, audio: true });
        localVideo.srcObject = localStream;
        localVideo.classList.remove('hidden');

        peerConnection = new RTCPeerConnection(config);

        // Add tracks
        localStream.getTracks().forEach(track => {
            peerConnection.addTrack(track, localStream);
        });

        peerConnection.ontrack = (event) => {
            remoteVideo.srcObject = event.streams[0];
            remoteStream = event.streams[0];
            
            // UI Updates
            remoteVideo.classList.remove('hidden');
            videoPlaceholder.classList.add('hidden');
            callControl.classList.remove('hidden');
        };

        peerConnection.onicecandidate = (event) => {
            if (event.candidate) {
                axios.post('/call/ice-candidate', {
                    to: incomingCall.from,
                    candidate: event.candidate,
                    call_id: incomingCall.call_id
                });
            }
        };

        // Set remote desc
        await peerConnection.setRemoteDescription(new RTCSessionDescription(incomingCall.offer));

        // Create Answer
        const answer = await peerConnection.createAnswer();
        await peerConnection.setLocalDescription(answer);

        // Send answer
        await axios.post('/call/accept', {
            caller_id: incomingCall.from,
            answer: answer,
            call_id: incomingCall.call_id
        });
        
        window.currentPeerId = incomingCall.from;
        window.currentCallId = incomingCall.call_id;

    } catch (error) {
        console.error('Error accepting call:', error);
    }
};

window.rejectCall = () => {
    if (!incomingCall) return;
    
    axios.post('/call/reject', {
        caller_id: incomingCall.from,
        call_id: incomingCall.call_id
    });
    
    incomingCallModal.classList.add('hidden');
    incomingCall = null;
};

window.endCall = () => {
    if (localStream) {
        localStream.getTracks().forEach(track => track.stop());
    }
    if (peerConnection) {
        peerConnection.close();
    }
    
    // Reset UI
    localVideo.srcObject = null;
    remoteVideo.srcObject = null;
    localVideo.classList.add('hidden');
    remoteVideo.classList.add('hidden');
    videoPlaceholder.classList.remove('hidden');
    callControl.classList.add('hidden');
    
    // Optional: Notify other party (not implemented efficiently here, relies on reload mostly)
    location.reload(); 
};

window.toggleAudio = () => {
    if (localStream) {
        const audioTrack = localStream.getAudioTracks()[0];
        if (audioTrack) {
            audioTrack.enabled = !audioTrack.enabled;
            // Visual feedback could be added here
            console.log('Audio ' + (audioTrack.enabled ? 'unmuted' : 'muted'));
        }
    }
};

window.toggleVideo = () => {
    if (localStream) {
        const videoTrack = localStream.getVideoTracks()[0];
        if (videoTrack) {
            videoTrack.enabled = !videoTrack.enabled;
            // Visual feedback could be added here
            console.log('Video ' + (videoTrack.enabled ? 'enabled' : 'disabled'));
        }
    }
};

// Event Listeners for Buttons
if (acceptCallBtn) acceptCallBtn.onclick = window.acceptCall;
if (rejectCallBtn) rejectCallBtn.onclick = window.rejectCall;

// Initialize Echo Listener
document.addEventListener('DOMContentLoaded', () => {
    if (typeof authUserId !== 'undefined') {
        console.log('Listening for events on user.' + authUserId);
        
        window.Echo.private('user.' + authUserId)
            .listen('StartVideoCall', (e) => {
                console.log('Incoming call:', e);
                incomingCall = e.data;
                incomingCall.from = e.data.from; // Ensure we map correctly
                incomingCall.offer = e.data.offer;
                incomingCall.call_id = e.data.call_id;
                
                callerNameSpan.innerText = e.data.caller_name || 'Unknown';
                incomingCallModal.classList.remove('hidden');
            })
            .listen('AcceptVideoCall', async (e) => {
                console.log('Call accepted:', e);
                if (peerConnection) {
                    await peerConnection.setRemoteDescription(new RTCSessionDescription(e.data.answer));
                }
            })
            .listen('RejectVideoCall', (e) => {
                console.log('Call rejected');
                alert('Call rejected');
                window.endCall();
            })
            .listen('IceCandidate', async (e) => {
                console.log('New ICE candidate:', e);
                if (peerConnection) {
                    await peerConnection.addIceCandidate(new RTCIceCandidate(e.data.candidate));
                }
            });
    }
});
