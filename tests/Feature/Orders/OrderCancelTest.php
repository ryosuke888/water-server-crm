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

    public function test_admin_can_cancel_order()
    {
        $user = User::factory()->create([
            'role' => Role::ADMIN->value,
        ]);

        $customer = Customer::factory()->create();

        ['plan' =>$plan, 'product' => $product, 'planProductPrice' => $planProductPrice] = $this->prepareOrderMasterData();

        $quantity = 2;

        $response = $this->actingAs($user)->post(route('customers.orders.store', $customer),
            $this->makeOrderPayload($customer, $plan, $product, $quantity));

        $response->assertRedirect(route('customers.orders.index', $customer));

        $order = Order::firstOrFail();

        $this->actingAs($user)->patch(route('customers.orders.cancel', compact('customer', 'order')),
            $this->cancelOrderPayload());

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
            'scheduled_delivery_date' => now()->addDays(10)->toDateString(),
            'scheduled_shipping_date' => now()->addDays(7)->toDateString(),
        ]);

        $this->assertDatabaseHas('order_histories', [
            'customer_id' => $customer->id,
            'order_id' => $order->id,
            'user_id' => $user->id,
            'order_code_snapshot' => $order->order_code,
            'action_type' => OrderHistoryActionType::CANCEL->value,
            'action_summary' => '受注をキャンセルしました',
        ]);

        $orderHistory = OrderHistory::where('action_type', OrderHistoryActionType::CANCEL)->firstOrFail();
        $beforeValues = $orderHistory->before_values;
        $afterValues = $orderHistory->after_values;

        $this->assertNull($beforeValues['remarks']);
        $this->assertEquals(OrderStatus::CANCELED->value, $afterValues['order_status']);
        $this->assertEquals(now()->addDays(10)->toDateString(), $afterValues['scheduled_delivery_date']);
        $this->assertEquals(now()->addDays(7)->toDateString(), $afterValues['scheduled_shipping_date']);
        $this->assertEquals('[キャンセル理由]:お客様都合のためキャンセル。', $afterValues['remarks']);
    }

    public function test_general_user_cannot_cancel_order()
    {
        $admin = User::factory()->create([
            'role' => Role::ADMIN->value,
        ]);

        $customer = Customer::factory()->create();

        ['plan' =>$plan, 'product' => $product, 'planProductPrice' => $planProductPrice] = $this->prepareOrderMasterData();

        $quantity = 2;

        $response = $this->actingAs($admin)->post(route('customers.orders.store', $customer),
            $this->makeOrderPayload($customer, $plan, $product, $quantity));

        $response->assertRedirect(route('customers.orders.index', $customer));

        $order = Order::firstOrFail();

        $roles = [Role::VIEWER, Role::SALES, Role::OPERATOR];

        foreach ($roles as $role) {
            $user = User::factory()->create([
                'role' => $role->value,
            ]);

            $response = $this->actingAs($user)->patch(route('customers.orders.cancel', compact('customer', 'order')),
            $this->cancelOrderPayload());

            $response->assertForbidden();

            $this->assertDatabaseHas('orders', [
                'customer_id' => $customer->id,
                'plan_id' => $plan->id,
                'product_id' => $product->id,
                'quantity' => $quantity,
                'order_status' => OrderStatus::RECEIVED->value,
            ]);

            $this->assertDatabaseMissing('order_histories', [
                'customer_id' => $customer->id,
                'action_type' => OrderHistoryActionType::CANCEL->value,
            ]);
        }
    }

    private function prepareOrderMasterData(): array
    {
        $plan = Plan::factory()->create();
        $product = Product::factory()->water()->create();
        $planProductPrice = PlanProductPrice::factory()
            ->forPlan($plan)
            ->forProduct($product)
            ->create([
                'price' => 3000
        ]);

        return compact('plan', 'product', 'planProductPrice');
    }

    private function makeOrderPayload (Customer $customer, Plan $plan, Product $product, int $quantity): array
    {
        return [
            'customer_id' => $customer->id,
            'plan_id' => $plan->id,
            'product_id' => $product->id,
            'quantity' => $quantity,
            'order_type' => OrderType::INITIAL->value,
            'scheduled_delivery_date' => now()->addDays(10)->toDateString(),
        ];
    }

    private function cancelOrderPayload(): array
    {
        return  [
            'cancel_reason' => 'お客様都合のためキャンセル。'
        ];
    }
}
