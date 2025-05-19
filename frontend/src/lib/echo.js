import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

// Cấu hình Laravel Echo với Pusher
window.Echo = new Echo({
    broadcaster: 'pusher',
    key: 'e242c9f071bc414eff61', // Lấy từ .env của backend
    cluster: 'ap1', // Lấy từ .env của backend
    forceTLS: true,
    wsHost: window.location.hostname,
    wsPort: 6001, // Hoặc cổng bạn sử dụng cho WebSockets
    disableStats: true
});
