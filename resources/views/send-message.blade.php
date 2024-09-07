<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Real-time Chat</title>
</head>
<body>
    <h1>Real-time Chat</h1>
    <div id="message-container">
        <!-- New messages will appear here -->
    </div>

    <!-- Include the Pusher JavaScript library -->
    <script src="https://js.pusher.com/8.0/pusher.min.js"></script>

    <script>
        // Initialize Pusher with your app key and cluster
        const pusher = new Pusher('your-pusher-app-key', {
            cluster: 'your-pusher-app-cluster',
            encrypted: true
        });

        // Subscribe to the 'chat-channel' channel
        const channel = pusher.subscribe('chat-channel');

        // Bind to the 'message-sent' event within that channel
        channel.bind('message-sent', function(data) {
            // Log the message received from the event
            console.log('Message received:', data.message);

            // Optionally, update the DOM with the message
            const messageContainer = document.getElementById('message-container');
            const newMessage = document.createElement('p');
            newMessage.textContent = data.message;
            messageContainer.appendChild(newMessage);
        });
    </script>
</body>
</html>
