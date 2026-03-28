<?php

namespace App\Services;

use App\Models\Order;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class OrderService {
    public function store(array $data): Order
    {
        $order = DB::transaction(function () use ($data) {
            $order = Order::create($data);
            $order->order_code = 'O' . str_pad((string) $order->id, 8, '0', STR_PAD_LEFT);
            $order->order_status = "受付";
            $order->shipping_company = "ヤマト運輸";
            $order->order_date = now()->toDateString();
            $scheduledDeliveryDate = Carbon::parse($data['scheduled_delivery_date']);
            $scheduledShippingDate = $scheduledDeliveryDate->copy()->subDays(3)->toDateString();
            $order->scheduled_shipping_date = $scheduledShippingDate;
            $order->save();
            return $order;
        });

        return $order;
    }
}
