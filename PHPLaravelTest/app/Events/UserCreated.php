<?php

namespace App\Events;

use App\Models\Order;
use App\Models\User;

class UserCreated
{
    /**
     * A new User has been created
     */
    public function __construct(User $user)
    {
        // Find any existing orders with an email matching the new user's email, and automatically re-assign them to this user
        foreach (Order::where('user_email', $user->email)->get() as $order) {
            $order->user_id = $user->id;
            $order->user_email = null;
            $order->customer_first_name = null;
            $order->customer_last_name = null;
            $order->save();
        }
    }
}
