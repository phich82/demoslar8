<?php

namespace App\Broadcasting;

use App\Models\User;
use App\Models\Order;

class OrderChannel
{
    public $order;

    /**
     * Create a new channel instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Authenticate the user's access to the channel.
     *
     * @param  \App\Models\User  $user
     * @param  int  $orderId
     * @return array|bool
     */
    public function join(User $user, $orderId)
    {
        $this->order = Order::findOrFail($orderId);
        if ($user->id === $this->order->user_id) {
            return [
                'user_id' => $user->id,
                'order' => $this->order,
            ];
        }
        return false;
    }
}
