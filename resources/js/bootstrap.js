window._ = require('lodash');

try {
    require('bootstrap');
} catch (e) {}

/**
 * We'll load the axios HTTP library which allows us to easily issue requests
 * to our Laravel back-end. This library automatically handles sending the
 * CSRF token as a header based on the value of the "XSRF" token cookie.
 */

window.axios = require('axios');

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

/**
 * Echo exposes an expressive API for subscribing to channels and listening
 * for events that are broadcast by Laravel. Echo and event broadcasting
 * allows your team to easily build robust real-time web applications.
 */

import Echo from 'laravel-echo';

window.Pusher = require('pusher-js');

window.Echo = new Echo({
    broadcaster: process.env.MIX_PUSHER_DRIVER,
    key: process.env.MIX_PUSHER_APP_KEY,
    cluster: process.env.MIX_PUSHER_APP_CLUSTER,
    wsHost: window.location.hostname,
    wsPort: process.env.MIX_PUSHER_WEBSOCKET_PORT || 6001,
    forceTLS: false,
    disableStats: true,
    encrypted: true,
    // namespace: 'App.Other.Namespace', // namespace for event. Default: App\Events
    authorizer: (channel, options) => {
        return {
            authorize: (socketId, callback) => {
                axios.post('/broadcasting/auth', { // '/api/broadcasting/auth'
                    socket_id: socketId,
                    channel_name: channel.name
                })
                .then(response => {
                    console.log({authorizer: response})
                    callback(false, response.data);
                })
                .catch(error => {
                    console.log({error: error})
                    callback(true, error);
                });
            }
        };
    },
});
