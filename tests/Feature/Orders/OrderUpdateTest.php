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

class OrderUpdateTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_update_order()
    {
        $user = User::factory()->create([
            'role' => Role::ADMIN->value,
        ]);

        $customer = Customer::factory()->create();

        $initialPlan = Plan::factory()->create();
        $initialProduct = Product::factory()->water()->create();
        $planProductPrice = PlanProductPrice::factory()
        ->forPlan($initialPlan)
        ->forProduct($initialProduct)
        ->create();

        $response = $this->actingAs($user)->post(route('customers.orders.store', $customer), [
            'customer_id' => $customer->id,
            'plan_id' => $initialPlan->id,
            'product_id' => $initialPlan->id,
            'quantity' => 2,
            'order_type' => OrderType::INITIAL->value,
            'scheduled_delivery_date' => '2026-04-20',
        ]);

        $response->assertRedirect(route('customers.orders.index', $customer));

        $order = Order::first();
        var_dump($order->order_status);

        $updatedPlan = Plan::factory()->create();
        $updatedProduct = Product::factory()->water()->create();
        $updatedPlanProductPrice = PlanProductPrice::factory()
            ->forPlan($updatedPlan)
            ->forProduct($updatedProduct)
            ->create();

        $quantity = 3;

        $response = $this->actingAs($user)->patch(route('customers.orders.update', compact('customer', 'order')), [
            'plan_id' => $updatedPlan->id,
            'product_id' => $updatedProduct->id,
            'quantity' => $quantity,
            'order_type' => OrderType::REGULAR->value,
            'order_status' => OrderStatus::PREPARING->value,
            'scheduled_delivery_date' => '2026-04-23',
        ]);

        $response->assertRedirect(route('customers.orders.show', compact('customer', 'order')));

        $this->assertDatabaseHas('orders', [
            'customer_id' => $customer->id,
            'plan_id' => $updatedPlan->id,
            'product_id' => $updatedProduct->id,
            'quantity' => $quantity,
            'unit_price' => $updatedPlanProductPrice->price,
            'subtotal_amount' => $updatedPlanProductPrice->price * $quantity,
            'order_type' => OrderType::REGULAR->value,
            'order_status' => OrderStatus::PREPARING->value,
            'shipping_company' => 'ヤマト運輸',
            'remarks' => null,
            'scheduled_delivery_date' => '2026-04-23',
            'scheduled_shipping_date' => '2026-04-20',
        ]);

        $this->assertDatabaseHas('order_histories', [
            'customer_id' => $customer->id,
            'order_id' => $order->id,
            'user_id' => $user->id,
            'order_code_snapshot' => $order->order_code,
            'action_type' => OrderHistoryActionType::UPDATE->value,
            'action_summary' => '受注情報を更新しました',
        ]);

        $orderHistory = OrderHistory::where('action_type', OrderHistoryActionType::UPDATE->value)->first();
        $beforeValues = $orderHistory->before_values;
        $afterValues = $orderHistory->after_values;

        $this->assertEquals($initialProduct->id, $beforeValues['product_id']);
        $this->assertEquals($initialPlan->id, $beforeValues['plan_id']);
        $this->assertEquals(2, $beforeValues['quantity']);

        $this->assertEquals($updatedProduct->id, $afterValues['product_id']);
        $this->assertEquals($updatedPlan->id, $afterValues['plan_id']);
        $this->assertEquals($quantity, $afterValues['quantity']);
        $this->assertEquals(OrderStatus::PREPARING->value, $afterValues['order_status']);
        $this->assertEquals('2026-04-23', $afterValues['scheduled_delivery_date']);
    }
}
