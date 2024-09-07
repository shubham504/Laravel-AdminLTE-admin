// require('./bootstrap');
// window.Echo.channel('chat-channel')
//     .listen('.message.sent', (e) => {
//         console.log(e.message);
//     });

window.Echo.channel('my-channel')
    .listen('App\\Events\\MyEvent', function (e) {
        alert('SSSSS'); // Correctly handle the event data
    });

