$(function() {
    // Presence Channels (via Channel Class)
    Echo.join(`test.1`)
        .here((users) => {
            console.log({ order: users })
        })
        .joining((user) => {
            console.log({ joining: user });
        })
        .leaving((user) => {
            console.log({ leaving: user });
        })
        .error((error) => {
            console.error({ order_error: error });
        });

    // Presence Channels
    let roomId = 1;
    Echo.join(`chat.${roomId}`)
        .here((users) => {
            console.log({ presence_channel: users })
        })
        .joining((user) => {
            console.log({ joining: user });
        })
        .leaving((user) => {
            console.log({ leaving: user });
        })
        .error((error) => {
            console.error({ presence_channel_error: error });
        })
        .listen('.ChatPresenceChannel', (e) => {
            console.log({ presence_channel: { message: e } });
        });

    // Private Channels
    let orderId = 1;
    // For security reason, prefix '.' right before event name
    Echo.channel(`orders.${orderId}`).listen('.OrderNormalChannel', (e) => {
        console.log({ channel: e });
    });
    Echo.private(`orders.${orderId}`).listen('.OrderPrivateChannel', (e) => {
        console.log({ private_channel: e });
    });
});
