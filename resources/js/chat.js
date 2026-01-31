
// Basic Chat and Online Status Logic using Laravel Echo

// We assume 'Echo' and 'axios' are available globally (via app.js bootstrap)

document.addEventListener('DOMContentLoaded', function () {
    const userIdMeta = document.querySelector('meta[name="user-id"]');
    const authUserId = userIdMeta ? parseInt(userIdMeta.content) : null;

    if (!authUserId) return;

    // --- Online Status ---
    const onlineUsersList = document.getElementById('onlineUsersList'); // You'll need to add this ID to your blade
    let unreadCounts = {};

    // Fetch initial unread counts
    axios.get('/api/messages/unread-counts')
        .then(response => {
            unreadCounts = response.data;
            // Apply immediately in case users are already loaded
            Object.keys(unreadCounts).forEach(userId => {
                updateUnreadBadge(userId, unreadCounts[userId]);
            });
        });

    // Debug Connection
    Echo.connector.pusher.connection.bind('connected', () => {
        console.log('✅ Connected to WebSocket server');
    });
    Echo.connector.pusher.connection.bind('error', (err) => {
        console.error('❌ WebSocket Error:', err);
        if (onlineUsersList) onlineUsersList.innerHTML = `<div class="text-center py-4 text-xs text-red-400">Connection Error. Retrying...</div>`;
    });

    const presenceChannel = Echo.join('online');

    presenceChannel
        .here((users) => {
            console.log('Online users:', users);
            updateOnlineUsersList(users);
        })
        .joining((user) => {
            console.log(user.name + ' joined.');
            addOnlineUser(user);
        })
        .leaving((user) => {
            console.log(user.name + ' left.');
            removeOnlineUser(user);
        })
        .error((error) => {
            console.error('Presence Channel Error:', error);
        });

    function updateOnlineUsersList(users) {
        if (!onlineUsersList) return;
        onlineUsersList.innerHTML = '';
        let othersCount = 0;

        users.forEach(user => {
            if (user.id !== authUserId) {
                onlineUsersList.innerHTML += renderUserListItem(user);
                othersCount++;
            }
        });

        if (othersCount === 0) {
            onlineUsersList.innerHTML = `<div class="text-center py-8 text-sm text-gray-400">No other users online.</div>`;
        }

        // Apply unread counts after rendering (or re-rendering)
        Object.keys(unreadCounts).forEach(userId => {
            updateUnreadBadge(userId, unreadCounts[userId]);
        });
    }

    function updateUnreadBadge(userId, count) {
        let badge = document.getElementById(`unread-badge-${userId}`);
        const userEl = document.getElementById(`user-online-${userId}`);

        if (!userEl) return; // User might be offline

        // Remove existing badge if it exists to rebuild or remove
        if (badge) badge.remove();

        if (count > 0) {
            badge = document.createElement('span');
            badge.id = `unread-badge-${userId}`;
            badge.className = 'bg-red-500 text-white text-xs font-bold px-2 py-0.5 rounded-full ml-auto';
            badge.innerText = count;

            // Append to the flex container inside user element
            userEl.querySelector('.flex.items-center.space-x-3').appendChild(badge);
        }
    }

    function addOnlineUser(user) {
        if (!onlineUsersList) return;
        // Check if already exists to avoid duplicates
        if (!document.getElementById(`user-online-${user.id}`)) {
            // Remove empty state message if exists
            if (onlineUsersList.innerHTML.includes('No other users online')) {
                onlineUsersList.innerHTML = '';
            }
            onlineUsersList.innerHTML += renderUserListItem(user);
        }
    }

    function removeOnlineUser(user) {
        const el = document.getElementById(`user-online-${user.id}`);
        if (el) el.remove();

        // If empty, show message
        if (onlineUsersList && onlineUsersList.children.length === 0) {
            onlineUsersList.innerHTML = `<div class="text-center py-8 text-sm text-gray-400">No other users online.</div>`;
        }
    }

    function renderUserListItem(user) {
        // Reuse your existing sidebar item HTML structure, adapted for dynamic insertion
        return `
            <div id="user-online-${user.id}" class="group flex items-center justify-between p-3 rounded-xl hover:bg-gray-50 border border-transparent hover:border-gray-100 transition duration-200 cursor-pointer" onclick="openChat(${user.id}, '${user.name.replace(/'/g, "\\'")}')">
                <div class="flex items-center space-x-3">
                    <div class="relative">
                        <div class="h-10 w-10 rounded-full bg-indigo-50 text-indigo-600 flex items-center justify-center font-bold text-sm">
                            ${user.name.charAt(0)}
                        </div>
                        <span class="absolute bottom-0 right-0 block h-2.5 w-2.5 rounded-full ring-2 ring-white bg-green-500"></span>
                    </div>
                    <div>
                        <h3 class="text-sm font-medium text-gray-900">${user.name}</h3>
                        <p class="text-xs text-gray-500 truncate">Online</p>
                    </div>
                </div>
            </div>
        `;
    }

    // --- Search Logic ---
    const emailSearchInput = document.getElementById('emailSearchInput');
    const searchBtn = document.getElementById('btnSearchUser');

    function searchUser() {
        const errorMsg = document.getElementById('searchError');
        const email = emailSearchInput.value;

        if (!email) return;

        errorMsg.classList.add('hidden');

        axios.post('/api/users/search', { email: email })
            .then(response => {
                const user = response.data;
                // Open chat with this user
                openChat(user.id, user.name);
                emailSearchInput.value = ''; // clear
            })
            .catch(error => {
                console.error(error);
                errorMsg.innerText = 'User not found.';
                errorMsg.classList.remove('hidden');
                setTimeout(() => errorMsg.classList.add('hidden'), 3000);
            });
    }

    if (searchBtn) {
        searchBtn.addEventListener('click', searchUser);
    }

    if (emailSearchInput) {
        emailSearchInput.addEventListener('keypress', function (e) {
            if (e.key === 'Enter') searchUser();
        });
    }

    // --- Messaging ---
    window.openChat = function (userId, userName) {
        console.log("Opening chat with", userName);

        // If chat with self, abort or show warning
        if (userId === authUserId) {
            alert("You cannot chat with yourself.");
            return;
        }

        const chatWindow = document.getElementById('chatWindow');
        const chatTitle = document.getElementById('chatTitle');
        const chatMessages = document.getElementById('chatMessages');

        if (chatWindow && chatTitle) {
            chatWindow.classList.remove('hidden');
            chatTitle.innerText = userName;
            chatMessages.innerHTML = ''; // Clear previous messages

            // Set current receiver ID for sending messages
            window.currentReceiverId = userId;

            // load messages
            axios.get(`/api/messages?user_id=${userId}`)
                .then(response => {
                    response.data.forEach(msg => {
                        appendMessage(msg, msg.sender_id === authUserId);
                    });
                });

            // Clear unread count locally
            if (unreadCounts[userId]) {
                unreadCounts[userId] = 0;
                updateUnreadBadge(userId, 0);
            }
        }
    };

    // Private Channel Listener for Messages
    Echo.private(`user.${authUserId}`)
        .listen('MessageSent', (e) => {
            console.log('New message:', e.message);
            // If chat window matches sender, append message
            if (window.currentReceiverId === e.message.sender_id) {
                appendMessage(e.message, false);
            } else {
                // Show notification / toast
                const senderName = e.message.sender.name;
                const senderId = e.message.sender.id;

                // Increment unread count
                unreadCounts[senderId] = (unreadCounts[senderId] || 0) + 1;
                updateUnreadBadge(senderId, unreadCounts[senderId]);

                // Toast Notification
                const toast = document.createElement('div');
                toast.className = 'fixed top-4 right-4 bg-white border-l-4 border-indigo-600 shadow-xl rounded-r px-4 py-3 z-50 flex items-center gap-3 animate-slide-in cursor-pointer';
                toast.innerHTML = `
                    <div class="rounded-full bg-indigo-100 p-2 text-indigo-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path></svg>
                    </div>
                    <div>
                        <p class="font-bold text-gray-800 text-sm">${senderName}</p>
                        <p class="text-gray-600 text-xs truncate max-w-[150px]">${e.message.body}</p>
                    </div>
                `;

                toast.onclick = () => {
                    openChat(senderId, senderName);
                    toast.remove();
                };

                document.body.appendChild(toast);

                // Play notification sound
                const audio = new Audio('https://assets.mixkit.co/active_storage/sfx/2869/2869-preview.mp3');
                audio.play().catch(e => console.log('Audio play failed', e));

                setTimeout(() => {
                    toast.classList.add('opacity-0', 'transition-opacity', 'duration-500');
                    setTimeout(() => toast.remove(), 500);
                }, 4000);
            }
        });

    window.sendMessage = function () {
        const input = document.getElementById('chatInput');
        const body = input.value;
        if (!body || !window.currentReceiverId) return;

        axios.post('/api/messages', {
            receiver_id: window.currentReceiverId,
            body: body
        }).then(response => {
            appendMessage(response.data, true);
            input.value = '';
        });
    };

    function appendMessage(message, isSelf) {
        const chatMessages = document.getElementById('chatMessages');
        if (!chatMessages) return;

        const align = isSelf ? 'text-right' : 'text-left';
        const bg = isSelf ? 'bg-indigo-100 ml-auto' : 'bg-gray-100';

        chatMessages.innerHTML += `
            <div class="mb-2 w-full flex ${isSelf ? 'justify-end' : 'justify-start'}">
                <div class="px-4 py-2 rounded-lg max-w-xs ${bg}">
                    <p class="text-sm text-gray-800">${message.body}</p>
                </div>
            </div>
        `;
        chatMessages.scrollTop = chatMessages.scrollHeight; // Scroll to bottom
    }
});
