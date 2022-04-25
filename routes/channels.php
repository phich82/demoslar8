<?php

use App\Models\Order;
use App\Broadcasting\TestBroadcast;
use Illuminate\Support\Facades\Broadcast;

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/

// Model Broadcasting
Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

// Presence Channels (via Channel (Broadcasting) Class)
Broadcast::channel('test.{id}', TestBroadcast::class);

// Normal & Private Channels
Broadcast::channel('orders.{orderId}', function ($user, $orderId) {
    return $user->id === Order::findOrNew($orderId)->user_id;
});

// Presence Channels (also private channels)
Broadcast::channel('chat.{roomId}', function ($user, $roomId) {
    // Return an `array` of users instead of `true` if the user is authorized to join the channel
    if (in_array($roomId, [1,2,3])) {
        return ['id' => $user->id, 'name' => $user->name];
    }
    // if ($user->canJoinRoom($roomId)) {
    //     return ['id' => $user->id, 'name' => $user->name];
    // }
    return false; // not authorized (return false or null)
}, [
    // could add middleware here
    // 'guards' => ['web', 'admin']
]);
