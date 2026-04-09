<?php

namespace Tests\Feature\Orders;

use App\Enums\OrderHistoryActionType;
use App\Enums\OrderStatus;
use App\Enums\OrderType;
use App\Enums\Role;
use App\Models\Customer;
use App\Models\Order;
use App\Models\OrderHistory;
use App\Models\Plan;
use App\Models\PlanProductPrice;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class OrderCancelTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_cancel_order()
    {
        $user = User::factory()->create([
            'role' => Role::ADMIN->value,
        ]);

        $customer = Customer::factory()->create();

        $product = Product::factory()->water()->create();
        $plan = Plan::factory()->create();
        $planProductPrice = PlanProductPrice::factory()
            ->forPlan($plan)
            ->forProduct($product)
            ->create([
                'price' => 3000
        ]);
        $quantity = 2;

        $response = $this->actingAs($user)->post(route('customers.orders.store', $customer), [
                'customer_id' => $customer->id,
                'plan_id' => $plan->id,
                'product_id' => $product->id,
                'quantity' => $quantity,
                'order_type' => OrderType::INITIAL->value,
                'scheduled_delivery_date' => '2026-04-20',
            ]);

        $response->assertRedirect(route('customers.orders.index', $customer));

        $order = Order::first();

        $this->actingAs($user)->patch(route('customers.orders.cancel', compact('customer', 'order')), [
            'cancel_reason' => 'お客様都合のためキャンセル。'
        ]);

        $this->assertDatabaseHas('orders', [
            'customer_id' => $customer->id,
            'plan_id' => $plan->id,
            'product_id' => $product->id,
            'quantity' => $quantity,
            'unit_price' => $planProductPrice->price,
            'subtotal_amount' => $planProductPrice->price * $quantity,
            'order_type' => OrderType::INITIAL->value,
            'order_status' => OrderStatus::CANCELED->value,
            'shipping_company' => 'ヤマト運輸',
            'remarks' => '[キャンセル理由]:お客様都合のためキャンセル。',
            'order_date' => now()->toDateString(),
            'scheduled_delivery_date' => '2026-04-20',
            'scheduled_shipping_date' => '2026-04-17',
        ]);

        $this->assertDatabaseHas('order_histories', [
            'customer_id' => $customer->id,
            'order_id' => $order->id,
            'user_id' => $user->id,
            'order_code_snapshot' => $order->order_code,
            'action_type' => OrderHistoryActionType::CANCEL->value,
            'action_summary' => '受注をキャンセルしました',
        ]);

        $orderHistory = OrderHistory::where('action_type', OrderHistoryActionType::CANCEL)->first();
        $beforeValues = $orderHistory->before_values;
        $afterValues = $orderHistory->after_values;

        $this->assertNull($beforeValues['remarks']);
        $this->assertEquals(OrderStatus::CANCELED->value, $afterValues['order_status']);
        $this->assertEquals('2026-04-20', $afterValues['scheduled_delivery_date']);
        $this->assertEquals('2026-04-17', $afterValues['scheduled_shipping_date']);
        $this->assertEquals('[キャンセル理由]:お客様都合のためキャンセル。', $afterValues['remarks']);
    }
}
