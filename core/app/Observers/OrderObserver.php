<?php

namespace App\Observers;

use App\Models\Order;
use App\Models\User;
use App\Services\LoyaltyService;

class OrderObserver
{
    public function __construct(protected LoyaltyService $loyaltyService)
    {
    }

    public function created(Order $order): void
    {
        $this->refreshTier($order);
    }

    public function updated(Order $order): void
    {
        // Only recalc if status or amount actually changed (avoid unnecessary queries)
        if ($order->wasChanged('order_status') || $order->wasChanged('order_amount')) {
            $this->refreshTier($order);
        }
    }

    protected function refreshTier(Order $order): void
    {
        $user = User::find($order->customer_id);

        if ($user) {
            $this->loyaltyService->updateUserTier($user);
        }
    }
}
