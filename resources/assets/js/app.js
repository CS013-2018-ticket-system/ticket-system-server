import Echo from "laravel-echo"

window.io = require('socket.io-client');

window.Echo = new Echo({
    broadcaster: 'socket.io',
    host: window.location.hostname + ':6001'
});

window.Echo.private('App.User.' + window.Laravel.user.id)
    .listen('.refund.reviewed', (e) => {
        var app = new Framework7();

        var $$ = Dom7;

        var notificationFull = app.notification.create({
            icon: '<i class="icon material-icons">account_circle</i>',
            title: '退款',
            titleRightText: 'now',
            subtitle: '申请退款成功',
            text: e.msg,
            closeButton: true,
        });

        notificationFull.open();

    });
