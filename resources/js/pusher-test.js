import Pusher from 'pusher-js';


Pusher.logToConsole = true;

const pusher = new Pusher('ae762f60670641422423', {
    cluster: 'mt1',
    forceTLS: true
});

console.log('Pusher instance created:', pusher);

const channel = pusher.subscribe('test-channel');

channel.bind('pusher:subscription_succeeded', function() {
    console.log('Successfully subscribed to test-channel');
});

channel.bind('pusher:subscription_error', function(status) {
    console.error('Subscription error:', status);
});

console.log('📡 Attempting to subscribe to test-channel...');