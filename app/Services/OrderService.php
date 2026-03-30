<?php

namespace App\Services;

use App\Models\Customer;
use App\Models\Order;
use App\Models\OrderHistory;
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

            $order = $order->refresh();
            $orderAfter = $order->only([
                'product_id',
                'plan_id',
                'quantity',
                'order_status',
                'scheduled_delivery_date',
            ]);

            OrderHistory::create([
                'customer_id' => $order->customer_id,
                'order_id' => $order->id,
                'user_id' => auth()->id(),
                'order_code_snapshot' => $order->order_code,
                'action_type' => 'create',
                'action_summary' => '受注情報を登録しました',
                'before_values' => null,
                'after_values'  => $orderAfter,
                'acted_at' => Carbon::now(),
            ]);

            return $order;
        });

        return $order;
    }

    public function update(array $validated, Customer $customer, Order $order): Order
    {
         return DB::transaction(function () use ($validated, $customer, $order) {
                $order = $customer->orders()->findOrFail($order->id);

                $orderBefore = $order->only([
                    'product_id',
                    'plan_id',
                    'quantity',
                    'order_status',
                    'scheduled_delivery_date',
                ]);

                $order->update($validated);
                $order = $order->refresh();

                $orderAfter = $order->only([
                    'product_id',
                    'plan_id',
                    'quantity',
                    'order_status',
                    'scheduled_delivery_date',
                ]);

                OrderHistory::create([
                    'customer_id' => $customer->id,
                    'order_id' => $order->id,
                    'user_id' => auth()->id(),
                    'order_code_snapshot' => $order->order_code,
                    'action_type' => 'update',
                    'action_summary' => '受注情報を更新しました',
                    'before_values' => $orderBefore,
                    'after_values'  => $orderAfter,
                    'acted_at' => Carbon::now(),
                ]);

                return $order;
            });

    }

    public function cancel(array $validated, Customer $customer, Order $order): Order
    {
        return DB::transaction(function() use($validated, $customer, $order) {
                $order = $customer->orders()->findOrFail($order->id);

                if ($order->order_status === 'キャンセル') {
                    throw new \RuntimeException('すでにキャンセル済みの受注です。');
                }

                $orderBefore = $order->only([
                    'order_status',
                    'scheduled_delivery_date',
                    'scheduled_shipping_date',
                    'remarks',
                ]);

                $remarks = $order->remarks ? $order->remarks . "\n[キャンセル理由]:" . $validated['cancel_reason'] : "[キャンセル理由]:" . $validated['cancel_reason'];

                $order->update([
                    'order_status' => "キャンセル",
                    'remarks' => $remarks,
                ]);
                $order->refresh();

                $orderAfter = $order->only([
                    'order_status',
                    'scheduled_delivery_date',
                    'scheduled_shipping_date',
                    'remarks',
                ]);

                OrderHistory::create([
                    'customer_id' => $customer->id,
                    'order_id' => $order->id,
                    'user_id' => auth()->id(),
                    'order_code_snapshot' => $order->order_code,
                    'action_type' => 'cancel',
                    'action_summary' => '受注をキャンセルしました',
                    'before_values' => $orderBefore,
                    'after_values'  => $orderAfter,
                    'acted_at' => Carbon::now(),
                ]);

                return $order;
            });
    }
}
