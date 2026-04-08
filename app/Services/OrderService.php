<?php

namespace App\Services;

use App\Enums\OrderHistoryActionType;
use App\Enums\OrderStatus;
use App\Models\Customer;
use App\Models\Order;
use App\Models\OrderHistory;
use App\Models\PlanProductPrice;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class OrderService {
    public function store(array $validated): Order
    {
        $order = DB::transaction(function () use ($validated) {
            $planProductPrice = PlanProductPrice::where('plan_id', $validated['plan_id'])->where('product_id', $validated['product_id'])->firstOrFail();

            $scheduledDeliveryDate = Carbon::parse($validated['scheduled_delivery_date']);
            $scheduledShippingDate = $scheduledDeliveryDate->copy()->subDays(3)->toDateString();

            $data = [
                'customer_id' => $validated['customer_id'],
                'product_id' => $validated['product_id'],
                'plan_id' => $validated['plan_id'],
                'order_type' => $validated['order_type'],
                'quantity' => $validated['quantity'],
                'remarks' => $validated['remarks'] ?? null,
                'scheduled_delivery_date' => $validated['scheduled_delivery_date'],
                'unit_price' => $planProductPrice->price,
                'subtotal_amount' => $planProductPrice->price * $validated['quantity'],
                'order_status' => '受付',
                'shipping_company' => 'ヤマト運輸',
                'order_date' => now()->toDateString(),
                'scheduled_shipping_date' => $scheduledShippingDate,
            ];

            $order = Order::create($data);

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
                'action_type' => OrderHistoryActionType::CREATE,
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

                $planProductPrice = PlanProductPrice::where('plan_id', $validated['plan_id'])->where('product_id', $validated['product_id'])->firstOrFail();
                $scheduledDeliveryDate = Carbon::parse($validated['scheduled_delivery_date']);
                $scheduledShippingDate = $scheduledDeliveryDate->copy()->subDays(3)->toDateString();

                $orderBefore = $order->only([
                    'product_id',
                    'plan_id',
                    'quantity',
                    'order_status',
                    'scheduled_delivery_date',
                ]);

                $data = [
                    'product_id' => $validated['product_id'],
                    'plan_id' => $validated['plan_id'],
                    'order_type' => $validated['order_type'],
                    'quantity' => $validated['quantity'],
                    'remarks' => $validated['remarks'] ?? null,
                    'scheduled_delivery_date' => $validated['scheduled_delivery_date'],
                    'unit_price' => $planProductPrice->price,
                    'subtotal_amount' => $planProductPrice->price * $validated['quantity'],
                    'order_status' => $validated['order_status'],
                    'shipping_company' => 'ヤマト運輸',
                    'scheduled_shipping_date' => $scheduledShippingDate,
                ];

                $order->update($data);
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
                    'action_type' => OrderHistoryActionType::UPDATE,
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
                    'order_status' => OrderStatus::CANCELED,
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
                    'action_type' => OrderHistoryActionType::CANCEL,
                    'action_summary' => '受注をキャンセルしました',
                    'before_values' => $orderBefore,
                    'after_values'  => $orderAfter,
                    'acted_at' => Carbon::now(),
                ]);

                return $order;
            });
    }
}
