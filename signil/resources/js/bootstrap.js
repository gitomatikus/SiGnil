window._ = require('lodash');

/**
 * We'll load jQuery and the Bootstrap jQuery plugin which provides support
 * for JavaScript based Bootstrap features such as modals and tabs. This
 * code may be modified to fit the specific needs of your application.
 */

try {
    window.Popper = require('popper.js').default;
    window.$ = window.jQuery = require('jquery');

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
// import Pusher from "pusher-js";
// import Echo from "laravel-echo"
//
// window.Echo = new Echo({
//     broadcaster: 'pusher',
//     key: '88187d78f94b47c6412e',
//     cluster: 'eu',
//     forceTLS: true
// });
//
// window.Echo.channel('game-room.1')
//     .listen('GotAskForAnswer', function(e) {
//         console.log('test', e, e.chatMessage);
//     });
//
window.Moment = require('moment-timezone');

import Echo from "laravel-echo"

window.io = require('socket.io-client');

window.Echo = new Echo({
    broadcaster: 'socket.io',
    host: window.location.hostname + ':6001'
});

import Game from "./game.js"
window.SiGnil = new Game();

window.Echo.channel('game.1')
    .listen('GotAskForAnswer', function(message) {
        let users = JSON.parse(localStorage.getItem('users'));
        if (!users) {
            users = {};
        }
        if (!users.hasOwnProperty(message.user)) {
            users[message.user] = message.time;
            localStorage.setItem('users', JSON.stringify(users))
        }
        window.SiGnil.refreshAsks(users);
    });

window.Echo.channel('game.1')
    .listen('ClearResults', function(message) {
        SiGnil.clearField();
    });
