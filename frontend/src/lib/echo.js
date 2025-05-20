import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

window.Pusher = Pusher;

// Cấu hình Laravel Echo với Pusher
window.Echo = new Echo({
    broadcaster: 'pusher',
    key: "e242c9f071bc414eff61",
    cluster: "ap1",
    forceTLS: true,
    // wsHost: window.location.hostname,
    // disableStats: true,
});
