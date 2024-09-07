<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chat App</title>
    <script src="{{ mix('js/app.js') }}" defer></script>
    <style>
        #chat-container {
            width: 600px;
            margin: 0 auto;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        #messages {
            height: 300px;
            overflow-y: auto;
            border: 1px solid #ddd;
            padding: 10px;
            margin-bottom: 10px;
            background-color: #f9f9f9;
        }
        #messages div {
            padding: 5px;
            border-bottom: 1px solid #ddd;
        }
        #user-name, #message {
            width: calc(100% - 22px);
            margin-bottom: 10px;
        }
        #send-button {
            width: 100%;
        }
    </style>
</head>
<body>
    <div id="chat-container">
        <div id="messages">
            <!-- Messages will be appended here -->
        </div>
        <input type="text" id="user-name" placeholder="Your Name">
        <textarea id="message" placeholder="Type your message..." rows="4"></textarea>
        <button id="send-button">Send</button>
    </div>

    <script>
        import Echo from 'laravel-echo';
import Pusher from 'pusher-js';
import axios from 'axios';

window.Pusher = Pusher;

window.Echo = new Echo({
    broadcaster: 'pusher',
    key: 'your-pusher-key', // Replace with your Pusher key
    cluster: 'mt1', // Replace with your Pusher cluster
    forceTLS: true
});

// Listen for incoming messages
window.Echo.channel('chat')
    .listen('MessageSent', (e) => {
        console.log('New message:', e.message);
        const messagesContainer = document.getElementById('messages');
        const messageElement = document.createElement('div');
        messageElement.textContent = `${e.message.user_name}: ${e.message.message}`;
        messagesContainer.appendChild(messageElement);
        messagesContainer.scrollTop = messagesContainer.scrollHeight; // Scroll to bottom
    });

// Send a message when the button is clicked
document.getElementById('send-button').addEventListener('click', () => {
    const userName = document.getElementById('user-name').value;
    const message = document.getElementById('message').value;

    axios.post('/send-message', {
        user_name: userName,
        message: message
    }).then(response => {
        console.log('Message sent:', response.data);
        document.getElementById('message').value = ''; // Clear the message input
    }).catch(error => {
        console.error('Error sending message:', error);
    });
});

    </script>
</body>
</html>
