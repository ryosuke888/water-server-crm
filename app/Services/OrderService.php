<?php

namespace App\Services;

use App\Models\Order;
use App\Models\PlanProductPrice;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class OrderService {
    public function store(array $data): Order
    {
        $order = DB::transaction(function () use ($data) {
            $planProductPrice = PlanProductPrice::where('plan_id', $data['plan_id'])->where('product_id', $data['product_id'])->firstOrFail();

            $scheduledDeliveryDate = Carbon::parse($data['scheduled_delivery_date']);
            $scheduledShippingDate = $scheduledDeliveryDate->copy()->subDays(3)->toDateString();

            $order = Order::create([
                'customer_id' => $data['customer_id'],
                'product_id' => $data['product_id'],
                'plan_id' => $data['plan_id'],
                'order_type' => $data['order_type'],
                'quantity' => $data['quantity'],
                'remarks' => $data['remarks'] ?? null,
                'scheduled_delivery_date' => $data['scheduled_delivery_date'],
                'unit_price' => $planProductPrice->price,
                'subtotal_amount' => $planProductPrice->price * $data['quantity'],
                'order_status' => '受付',
                'shipping_company' => 'ヤマト運輸',
                'order_date' => now()->toDateString(),
                'scheduled_shipping_date' => $scheduledShippingDate,
            ]);

            $order->order_code = 'O' . str_pad((string) $order->id, 8, '0', STR_PAD_LEFT);
            $order->save();

            return $order;
        });

        return $order;
    }
}
