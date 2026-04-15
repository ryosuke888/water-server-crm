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
use Illuminate\Validation\ValidationException;
use RuntimeException;

class OrderService {
    private const SHIPPING_COMPANY = 'ヤマト運輸';

    public function store(array $validated, int $userId): Order
    {
        $order = DB::transaction(function () use ($validated, $userId) {
            $planId = $validated['plan_id'];
            $productId = $validated['product_id'];
            $planProductPrice = $this->findPlanProductPrice($planId, $productId);
            $orderStatus = OrderStatus::RECEIVED;

            $scheduledDeliveryDate = Carbon::parse($validated['scheduled_delivery_date']);

            $scheduledShippingDate = $this->calculateScheduledShippingDate($scheduledDeliveryDate);

            $data = $this->makeOrderData($validated, $planProductPrice, $orderStatus, $scheduledShippingDate);
            $data['customer_id'] = $validated['customer_id'];
            $data['order_date'] = Carbon::now()->toDateString();

            $order = Order::create($data);

            $order->order_code = 'O' . str_pad((string) $order->id, 8, '0', STR_PAD_LEFT);
            $order->save();

            $order->refresh();

            $orderBefore = null;
            $orderAfter = $this->makeOrderSnapshot($order);

            $actionSummary = '受注情報を登録しました。';
            $actionType = OrderHistoryActionType::CREATE;

            $this->createOrderHistory($order, $userId ,$actionType, $orderBefore, $orderAfter, $actionSummary);

            return $order;
        });

        return $order;
    }

    public function update(array $validated, Customer $customer, Order $order, int $userId): Order
    {
         return DB::transaction(function () use ($validated, $customer, $order, $userId) {
                $order = $customer->orders()->findOrFail($order->id);

                $toStatus = OrderStatus::from($validated['order_status']);

                if (!$order->order_status->canTransitionTo($toStatus)) {
                    throw ValidationException::withMessages([
                        'order_status' => 'このステータスは変更できません。',
                    ]);
                }

                $planId = $validated['plan_id'];
                $productId = $validated['product_id'];
                $planProductPrice = $this->findPlanProductPrice($planId, $productId);
                $scheduledDeliveryDate = Carbon::parse($validated['scheduled_delivery_date']);

                $scheduledShippingDate = $this->calculateScheduledShippingDate($scheduledDeliveryDate);

                $orderBefore = $this->makeOrderSnapshot($order);

                $data = $this->makeOrderData($validated, $planProductPrice, $toStatus, $scheduledShippingDate);

                $order->update($data);
                $order->refresh();

                $orderAfter = $this->makeOrderSnapshot($order);

                $actionSummary = '受注情報を更新しました。';
                $actionType = OrderHistoryActionType::UPDATE;

                $this->createOrderHistory($order, $userId, $actionType, $orderBefore, $orderAfter, $actionSummary);

                return $order;
            });

    }

    public function cancel(array $validated, Customer $customer, Order $order, int $userId): Order
    {
        return DB::transaction(function() use($validated, $customer, $order, $userId) {
                $order = $customer->orders()->findOrFail($order->id);

                $toStatus = OrderStatus::CANCELED;

                if ($order->order_status === $toStatus) {
                    throw new \RuntimeException('すでにキャンセル済みの受注です。');
                }

                if (!$order->order_status->canTransitionTo($toStatus)) {
                    throw ValidationException::withMessages([
                        'order_status' => 'このステータスは変更できません。',
                    ]);
                }

                $orderBefore = $this->makeOrderSnapshot($order);

                $remarks = $order->remarks ? $order->remarks . "\n[キャンセル理由]:" . $validated['cancel_reason'] : "[キャンセル理由]:" . $validated['cancel_reason'];

                $order->update([
                    'order_status' => OrderStatus::CANCELED,
                    'remarks' => $remarks,
                ]);
                $order->refresh();

                $orderAfter = $this->makeOrderSnapshot($order);

                $actionSummary = '受注をキャンセルしました。';
                $actionType = OrderHistoryActionType::CANCEL;

                $this->createOrderHistory($order, $userId, $actionType, $orderBefore, $orderAfter, $actionSummary);

                return $order;
            });
    }

    private function findPlanProductPrice(int $planId, int $productId): PlanProductPrice
    {
        return PlanProductPrice::where([
            'plan_id' => $planId,
            'product_id' => $productId,
        ])->firstOrFail();
    }

    private function makeOrderData(array $validated, PlanProductPrice $planProductPrice, OrderStatus $orderStatus, string $scheduledShippingDate): array
    {
        return [
            'product_id' => $validated['product_id'],
            'plan_id' => $validated['plan_id'],
            'quantity' => $validated['quantity'],
            'unit_price' => $planProductPrice->price,
            'subtotal_amount' => $planProductPrice->price * $validated['quantity'],
            'order_type' => $validated['order_type'],
            'order_status' => $orderStatus,
            'shipping_company' => self::SHIPPING_COMPANY,
            'scheduled_delivery_date' => $validated['scheduled_delivery_date'],
            'scheduled_shipping_date' => $scheduledShippingDate,
            'remarks' => $validated['remarks'] ?? null,
        ];
    }

    private function calculateScheduledShippingDate(Carbon $scheduledDeliveryDate): string
    {
        return $scheduledDeliveryDate->copy()->subDays(3)->toDateString();;
    }

    private function makeOrderSnapshot(Order $order): array
    {
        return $order->only([
            'product_id',
            'plan_id',
            'quantity',
            'order_status',
            'scheduled_delivery_date',
            'remarks',
        ]);
    }
     private function createOrderHistory(Order $order, int $userId, OrderHistoryActionType $actionType , ?array $orderBefore, array $orderAfter, string $actionSummary): void
     {
        OrderHistory::create([
            'customer_id' => $order->customer_id,
            'order_id' => $order->id,
            'user_id' => $userId,
            'order_code_snapshot' => $order->order_code,
            'action_type' => $actionType,
            'action_summary' => $actionSummary,
            'before_values' => $orderBefore,
            'after_values'  => $orderAfter,
            'acted_at' => Carbon::now(),
        ]);
     }
}
