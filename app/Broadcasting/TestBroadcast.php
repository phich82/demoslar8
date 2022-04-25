<?php

namespace App\Broadcasting;

use App\Models\User;

class TestBroadcast
{
    public $data;

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
     * @param  int  $id
     * @return array|bool
     */
    public function join(User $user, $id = null)
    {
        $this->data = ['id' => $id];
        if (!empty($this->data)) {
            return [
                'user_id' => $user->id,
                'data' => $this->data,
            ];
        }
        return false;
    }
}
