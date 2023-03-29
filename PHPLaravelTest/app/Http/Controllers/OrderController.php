<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    /**
     * Shows order by id
     */
    public function show(int $id)
    {
        $order = Order::findOrFail($id);

        if (!$order) {
            self::abort(404);
        }

        if (!$order->belongsToUser(Auth::user()) || in_array($order->merch_status, [
            Order::STATUS_UNPAID,
            Order::STATUS_FAILED_PAYMENT,
        ]) || in_array($order->club_status, [
            Order::STATUS_UNPAID,
            Order::STATUS_FAILED_PAYMENT,
        ])) {
            self::abort(403, ['resourceType' => 'order']);
        }

        $order = $order->updateClubStatusFromGateway();

        return parent::view('my-account.orders.show', compact('order'));
    }
}
