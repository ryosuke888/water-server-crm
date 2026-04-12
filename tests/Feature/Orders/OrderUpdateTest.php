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
use Illuminate\Support\Carbon;
use Tests\TestCase;

class OrderUpdateTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_update_order()
    {
        $user = $this->makeUser(Role::ADMIN);

        $customer = Customer::factory()->create();

        ['plan' => $initialPlan, 'product' => $initialProduct] = $this->prepareOrderMasterData();

        $order = Order::factory()->create([
            'customer_id' => $customer->id,
            'plan_id' => $initialPlan->id,
            'product_id' => $initialProduct->id,
        ]);

        ['plan' => $updatedPlan, 'product' => $updatedProduct, 'planProductPrice' => $updatedPlanProductPrice] = $this->prepareOrderMasterData();

        $quantity = 3;

        $response = $this->actingAs($user)->patch(route('customers.orders.update', compact('customer', 'order')),
            $this->updateOrderPayload($updatedPlan, $updatedProduct, $quantity));

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
            'scheduled_delivery_date' => Carbon::now()->addDays(10)->toDateString(),
            'scheduled_shipping_date' => Carbon::now()->addDays(7)->toDateString(),
        ]);

        $this->assertDatabaseHas('order_histories', [
            'customer_id' => $customer->id,
            'order_id' => $order->id,
            'user_id' => $user->id,
            'order_code_snapshot' => $order->order_code,
            'action_type' => OrderHistoryActionType::UPDATE->value,
            'action_summary' => '受注情報を更新しました',
        ]);

        $orderHistory = OrderHistory::where('action_type', OrderHistoryActionType::UPDATE->value)->firstOrFail();
        $beforeValues = $orderHistory->before_values;
        $afterValues = $orderHistory->after_values;

        $this->assertEquals($initialProduct->id, $beforeValues['product_id']);
        $this->assertEquals($initialPlan->id, $beforeValues['plan_id']);
        $this->assertEquals(2, $beforeValues['quantity']);

        $this->assertEquals($updatedProduct->id, $afterValues['product_id']);
        $this->assertEquals($updatedPlan->id, $afterValues['plan_id']);
        $this->assertEquals($quantity, $afterValues['quantity']);
        $this->assertEquals(OrderStatus::PREPARING->value, $afterValues['order_status']);
        $this->assertEquals(Carbon::now()->addDays(10)->toDateString(), $afterValues['scheduled_delivery_date']);
    }

    public function test_viewer_cannot_update_order()
    {
        $admin= $this->makeUser(Role::ADMIN);

        $customer = Customer::factory()->create();

        ['plan' => $initialPlan, 'product' => $initialProduct] = $this->prepareOrderMasterData();

        $order = Order::factory()->create([
            'customer_id' => $customer->id,
            'plan_id' => $initialPlan->id,
            'product_id' => $initialProduct->id,
        ]);

        ['plan' => $updatedPlan, 'product' => $updatedProduct] = $this->prepareOrderMasterData();

        $quantity = 3;

        $viewer= $this->makeUser(Role::VIEWER);

        $response = $this->actingAs($viewer)->patch(route('customers.orders.update', compact('customer', 'order')),
            $this->updateOrderPayload($updatedPlan, $updatedProduct, $quantity));

        $response->assertForbidden();

        $this->assertDatabaseHas('orders', [
            'customer_id' => $customer->id,
            'plan_id' => $initialPlan->id,
            'product_id' => $initialProduct->id,
            'quantity' => 2,
            'order_status' => OrderStatus::RECEIVED->value,
        ]);

        $this->assertDatabaseMissing('order_histories', [
            'order_id' => $order->id,
            'action_type' => OrderHistoryActionType::UPDATE->value,
        ]);
        $this->assertDatabaseCount('order_histories', 0);
    }

    public function test_guest_cannot_update_order()
    {
        $admin= $this->makeUser(Role::ADMIN);

        $customer = Customer::factory()->create();

        ['plan' => $initialPlan, 'product' => $initialProduct,] = $this->prepareOrderMasterData();

        $order = Order::factory()->create([
            'customer_id' => $customer->id,
            'plan_id' => $initialPlan->id,
            'product_id' => $initialProduct->id,
        ]);

        ['plan' => $updatedPlan, 'product' => $updatedProduct] = $this->prepareOrderMasterData();

        $quantity = 3;

        $response = $this->patch(route('customers.orders.update', compact('customer', 'order')),
            $this->updateOrderPayload($updatedPlan, $updatedProduct, $quantity));

        $response->assertRedirect(route('login'));

        $this->assertDatabaseHas('orders', [
            'customer_id' => $customer->id,
            'plan_id' => $initialPlan->id,
            'product_id' => $initialProduct->id,
            'quantity' => 2,
            'order_status' => OrderStatus::RECEIVED->value,
        ]);

        $this->assertDatabaseMissing('order_histories', [
            'order_id' => $order->id,
            'action_type' => OrderHistoryActionType::UPDATE->value,
        ]);
        $this->assertDatabaseCount('order_histories', 0);
    }

    public function test_cannot_update_order_when_quantity_is_invalid()
    {
        $user = $this->makeUser(Role::ADMIN);

        $customer = Customer::factory()->create();

        ['plan' => $initialPlan, 'product' => $initialProduct] = $this->prepareOrderMasterData();

        $order = Order::factory()->create([
            'customer_id' => $customer->id,
            'plan_id' => $initialPlan->id,
            'product_id' => $initialProduct->id,
        ]);

        ['plan' => $updatedPlan, 'product' => $updatedProduct, 'planProductPrice' => $updatedPlanProductPrice] = $this->prepareOrderMasterData();

        $quantity = 0;

        $response = $this->from(route('customers.orders.show', compact('customer', 'order')))->actingAs($user)->patch(route('customers.orders.update', compact('customer', 'order')),
            $this->updateOrderPayload($updatedPlan, $updatedProduct, $quantity));

        $response->assertRedirect(route('customers.orders.show', compact('customer', 'order')));
        $response->assertSessionHasErrors('quantity');

        $this->assertDatabaseHas('orders', [
            'customer_id' => $customer->id,
            'plan_id' => $initialPlan->id,
            'product_id' => $initialProduct->id,
            'quantity' => 2,
            'order_status' => OrderStatus::RECEIVED->value,
        ]);

        $this->assertDatabaseMissing('order_histories', [
            'order_id' => $order->id,
            'action_type' => OrderHistoryActionType::UPDATE->value,
        ]);
        $this->assertDatabaseCount('order_histories', 0);
    }

    public function test_cannot_update_order_when_scheduled_delivery_date_is_invalid()
    {
        $user = $this->makeUser(Role::ADMIN);

        $customer = Customer::factory()->create();

        ['plan' => $initialPlan, 'product' => $initialProduct] = $this->prepareOrderMasterData();

        $order = Order::factory()->create([
            'customer_id' => $customer->id,
            'plan_id' => $initialPlan->id,
            'product_id' => $initialProduct->id,
        ]);

        ['plan' => $updatedPlan, 'product' => $updatedProduct, 'planProductPrice' => $updatedPlanProductPrice] = $this->prepareOrderMasterData();

        $quantity = 3;

        $response = $this->from(route('customers.orders.show', compact('customer', 'order')))->actingAs($user)->patch(route('customers.orders.update', compact('customer', 'order')),
            array_merge($this->updateOrderPayload($updatedPlan, $updatedProduct, $quantity), [
                'scheduled_delivery_date' => now()->addDays(2)->toDateString(),
            ]));

        $response->assertRedirect(route('customers.orders.show', compact('customer', 'order')));
        $response->assertSessionHasErrors('scheduled_delivery_date');

        $this->assertDatabaseHas('orders', [
            'customer_id' => $customer->id,
            'plan_id' => $initialPlan->id,
            'product_id' => $initialProduct->id,
            'quantity' => 2,
            'order_status' => OrderStatus::RECEIVED->value,
        ]);

        $this->assertDatabaseMissing('order_histories', [
            'order_id' => $order->id,
            'action_type' => OrderHistoryActionType::UPDATE->value,
        ]);
        $this->assertDatabaseCount('order_histories', 0);
    }

    private function makeUser(Role $role): User
    {
        return User::factory()->create([
            'role' => $role->value,
        ]);
    }

    private function prepareOrderMasterData(): array
    {
        $product = Product::factory()->water()->create();
        $plan = Plan::factory()->create();
        $planProductPrice = PlanProductPrice::factory()
            ->forPlan($plan)
            ->forProduct($product)
            ->create();

        return compact('product', 'plan', 'planProductPrice');
    }

    private function makeOrderPayload (Customer $customer, Plan $plan, Product $product, int $quantity): array
    {
        return [
            'customer_id' => $customer->id,
            'plan_id' => $plan->id,
            'product_id' => $product->id,
            'quantity' => $quantity,
            'order_type' => OrderType::INITIAL->value,
            'scheduled_delivery_date' => Carbon::now()->addDays(10)->toDateString(),
        ];
    }

    private function updateOrderPayload(Plan $plan, Product $product, int $quantity): array
    {
        return [
            'plan_id' => $plan->id,
            'product_id' => $product->id,
            'quantity' => $quantity,
            'order_type' => OrderType::REGULAR->value,
            'order_status' => OrderStatus::PREPARING->value,
            'scheduled_delivery_date' => Carbon::now()->addDays(10)->toDateString(),
        ];
    }
}
