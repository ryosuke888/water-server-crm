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

class OrderCancelTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_cancel_order()
    {
        $user= $this->makeUser(Role::ADMIN);

        $customer = Customer::factory()->create();

        ['plan' =>$plan, 'product' => $product] = $this->prepareOrderMasterData();

        $quantity = 2;

        $order = $this->makeOrder($customer, $plan, $product, $quantity);

        $response = $this->actingAs($user)->patch(route('customers.orders.cancel', compact('customer', 'order')),
            $this->cancelOrderPayload());

        $response->assertRedirect(route('customers.orders.index', $customer));

        $this->assertDatabaseHas('orders', [
            'customer_id' => $customer->id,
            'plan_id' => $plan->id,
            'product_id' => $product->id,
            'quantity' => $quantity,
            'order_status' => OrderStatus::CANCELED->value,
            'remarks' => '[キャンセル理由]:お客様都合のためキャンセル。',
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
        $this->assertEquals(Carbon::now()->addDays(10)->toDateString(), $afterValues['scheduled_delivery_date']);
        $this->assertEquals(Carbon::now()->addDays(7)->toDateString(), $afterValues['scheduled_shipping_date']);
        $this->assertEquals('[キャンセル理由]:お客様都合のためキャンセル。', $afterValues['remarks']);
    }

    public function test_general_user_cannot_cancel_order()
    {
        $admin= $this->makeUser(Role::ADMIN);

        $customer = Customer::factory()->create();

        ['plan' =>$plan, 'product' => $product] = $this->prepareOrderMasterData();

        $quantity = 2;

        $order = $this->makeOrder($customer, $plan, $product, $quantity);

        $roles = [Role::VIEWER, Role::SALES, Role::OPERATOR];

        foreach ($roles as $role) {
            $user = $this->makeUser($role);

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
            $this->assertDatabaseCount('order_histories', 0);
        }
    }

    public function test_guest_cannot_cancel_order()
    {
        $admin= $this->makeUser(Role::ADMIN);

        $customer = Customer::factory()->create();

        ['plan' =>$plan, 'product' => $product] = $this->prepareOrderMasterData();

        $quantity = 2;

        $order = $this->makeOrder($customer, $plan, $product, $quantity);

        $response = $this->patch(route('customers.orders.cancel', compact('customer', 'order')),
        $this->cancelOrderPayload());

        $response->assertRedirect(route('login'));

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
        $this->assertDatabaseCount('order_histories', 0);
    }

    public function test_cannot_cancel_order_when_the_reason_is_empty()
    {
        $user= $this->makeUser(Role::ADMIN);

        $customer = Customer::factory()->create();

        ['plan' =>$plan, 'product' => $product] = $this->prepareOrderMasterData();

        $quantity = 2;

        $order = $this->makeOrder($customer, $plan, $product, $quantity);

        $response = $this->from(route('customers.orders.show', compact('customer', 'order')))->actingAs($user)->patch(route('customers.orders.cancel', compact('customer', 'order')),
            array_merge($this->cancelOrderPayload(), [
                'cancel_reason' => '',
            ]));

        $response->assertRedirect(route('customers.orders.show', compact('customer', 'order')));
        $response->assertSessionHasErrors('cancel_reason');

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
        $this->assertDatabaseCount('order_histories', 0);
    }

    private function makeOrder(Customer $customer, Plan $plan, Product $product, int $quantity): Order
    {
        return Order::factory()->create([
            'customer_id' => $customer->id,
            'plan_id' => $plan->id,
            'product_id' => $product->id,
            'quantity' => $quantity,
            'order_status' => OrderStatus::RECEIVED->value,
            'scheduled_delivery_date' => Carbon::now()->addDays(10)->toDateString(),
            'scheduled_shipping_date' => Carbon::now()->addDays(7)->toDateString(),
        ]);
    }

    private function makeUser(Role $role): User
    {
        return User::factory()->create([
            'role' => $role->value,
        ]);
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
            'scheduled_delivery_date' => Carbon::now()->addDays(10)->toDateString(),
        ];
    }

    private function cancelOrderPayload(): array
    {
        return  [
            'cancel_reason' => 'お客様都合のためキャンセル。',
        ];
    }
}
