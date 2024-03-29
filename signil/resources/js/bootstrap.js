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
window.Moment = require('moment-timezone');

import Echo from "laravel-echo"


import Game from "./game.js"
window.SiGnil = new Game();

import Questions from "./questions.js"
window.Questions = new Questions();

window.io = require('socket.io-client');

window.Echo = new Echo({
    broadcaster: 'socket.io',
    host: window.location.hostname + ':6001'
});

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

        let music = $('audio')[0];
        if (music !== undefined){
            music.volume = 0.2;
            music.pause();
        }
        let video = $('video')[0];
        if (video !== undefined){
            video.volume = 0.2;
            video.pause();
        }
});

window.Echo.channel('game.1')
    .listen('Media', function(message) {
        let music = $('audio');
        let video = $('video');


        if (message.state === 'play') {
            music.each(function(index, element){
                element.play()
            });
            video.each(function(index, element){
                element.play()
            });
        }
        if (message.state === 'pause') {
            music.each(function(index, element){
                element.pause()
            });
            video.each(function(index, element){
                element.pause()
            });
        }
    });
// window.Echo.channel('game.1')
//     .listen('ClearResults', function(message) {
//         SiGnil.clearField();
//     });

window.bootstrapTable = require('bootstrap-table');
window.ProgressBar = require('progressbar.js');
