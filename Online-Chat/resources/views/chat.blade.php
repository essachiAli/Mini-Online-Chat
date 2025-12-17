<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Online Chat</title>
    @vite('resources/css/app.css')

</head>
<body class="bg-gray-100 h-screen flex items-center justify-center">
    <div class="w-full max-w-2xl h-[600px] bg-white rounded-lg shadow-xl flex flex-col">
        <!-- Header -->
        <div class="bg-green-600 text-white p-4 rounded-t-lg">
            <h1 class="text-xl font-semibold">Online Chat Room</h1>
            <p class="text-sm opacity-90" id="username-display"></p>
        </div>

        <!-- Messages Container -->
        <div id="messages-container" class="flex-1 overflow-y-auto p-4 space-y-3 bg-gray-50">
            <!-- Messages will be inserted here -->
        </div>

        <!-- Input Area -->
        <div class="p-4 bg-white border-t border-gray-200">
            <div class="flex space-x-2">
                <input
                    type="text"
                    id="message-input"
                    placeholder="Type a message..."
                    class="flex-1 px-4 py-3 border border-gray-300 rounded-full focus:outline-none focus:ring-2 focus:ring-green-500"
                    maxlength="500"
                >
                <button
                    id="send-button"
                    class="bg-green-600 text-white px-6 py-3 rounded-full hover:bg-green-700 transition-colors font-semibold"
                >
                    Send
                </button>
            </div>
        </div>
    </div>

    <!-- Username Modal -->
    <div id="username-modal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white p-8 rounded-lg shadow-2xl max-w-md w-full">
            <h2 class="text-2xl font-bold mb-4 text-gray-800">Enter Your Nickname</h2>
            <input
                type="text"
                id="username-input"
                placeholder="Your nickname..."
                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 mb-4"
                maxlength="50"
            >
            <button
                id="username-submit"
                class="w-full bg-green-600 text-white py-3 rounded-lg hover:bg-green-700 transition-colors font-semibold"
            >
                Start Chatting
            </button>
        </div>
    </div>

    <script>
        let username = localStorage.getItem('chat_username');
        let lastMessageId = 0;
        let isAutoScrollEnabled = true;

        const messagesContainer = document.getElementById('messages-container');
        const messageInput = document.getElementById('message-input');
        const sendButton = document.getElementById('send-button');
        const usernameModal = document.getElementById('username-modal');
        const usernameInput = document.getElementById('username-input');
        const usernameSubmit = document.getElementById('username-submit');
        const usernameDisplay = document.getElementById('username-display');

        // CSRF Token
        const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

        // Show/Hide Username Modal
        if (!username) {
            usernameModal.classList.remove('hidden');
        } else {
            usernameModal.classList.add('hidden');
            usernameDisplay.textContent = `Chatting as: ${username}`;
            startPolling();
        }

        // Username Submit
        usernameSubmit.addEventListener('click', () => {
            const enteredUsername = usernameInput.value.trim();
            if (enteredUsername && enteredUsername.length <= 50) {
                username = enteredUsername;
                localStorage.setItem('chat_username', username);
                usernameModal.classList.add('hidden');
                usernameDisplay.textContent = `Chatting as: ${username}`;
                startPolling();
            } else {
                alert('Please enter a valid nickname (max 50 characters)');
            }
        });

        usernameInput.addEventListener('keypress', (e) => {
            if (e.key === 'Enter') {
                usernameSubmit.click();
            }
        });

        // Send Message
        async function sendMessage() {
            const content = messageInput.value.trim();
            if (!content || !username) return;

            try {
                const response = await fetch('/messages', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify({
                        username: username,
                        content: content
                    })
                });

                const data = await response.json();

                if (data.success) {
                    messageInput.value = '';
                    fetchMessages();
                } else {
                    alert('Failed to send message');
                }
            } catch (error) {
                console.error('Error sending message:', error);
            }
        }

        sendButton.addEventListener('click', sendMessage);
        messageInput.addEventListener('keypress', (e) => {
            if (e.key === 'Enter') {
                sendMessage();
            }
        });

        // Fetch Messages
        async function fetchMessages() {
            try {
                const response = await fetch('/messages');
                const data = await response.json();

                if (data.success) {
                    renderMessages(data.messages);
                }
            } catch (error) {
                console.error('Error fetching messages:', error);
            }
        }

        // Render Messages
        function renderMessages(messages) {
            messagesContainer.innerHTML = '';

            messages.forEach(message => {
                const messageDiv = document.createElement('div');
                const isOwnMessage = message.username === username;

                messageDiv.className = `flex ${isOwnMessage ? 'justify-end' : 'justify-start'}`;

                messageDiv.innerHTML = `
                    <div class="max-w-xs lg:max-w-md">
                        ${!isOwnMessage ? `<p class="text-xs text-gray-600 mb-1 ml-2">${escapeHtml(message.username)}</p>` : ''}
                        <div class="${isOwnMessage ? 'bg-green-500 text-white' : 'bg-white text-gray-800'} rounded-lg px-4 py-2 shadow">
                            <p class="break-words">${escapeHtml(message.content)}</p>
                            <p class="text-xs ${isOwnMessage ? 'text-green-100' : 'text-gray-500'} mt-1 text-right">${message.created_at}</p>
                        </div>
                    </div>
                `;

                messagesContainer.appendChild(messageDiv);

                if (message.id > lastMessageId) {
                    lastMessageId = message.id;
                }
            });

            if (isAutoScrollEnabled) {
                scrollToBottom();
            }
        }

        // Scroll to Bottom
        function scrollToBottom() {
            messagesContainer.scrollTop = messagesContainer.scrollHeight;
        }

        // Check if user is at bottom
        messagesContainer.addEventListener('scroll', () => {
            const threshold = 100;
            const position = messagesContainer.scrollTop + messagesContainer.clientHeight;
            const height = messagesContainer.scrollHeight;
            isAutoScrollEnabled = height - position < threshold;
        });

        // Escape HTML
        function escapeHtml(text) {
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        }

        // Start Polling
        function startPolling() {
            fetchMessages();
            setInterval(fetchMessages, 2500);
        }
    </script>
</body>
</html>
