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

class OrderStoreTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_store_order()
    {
        $user = $this->makeUser(Role::ADMIN);

        ['customer' => $customer, 'plan' => $plan, 'product' => $product, 'planProductPrice' => $planProductPrice] = $this->prepareOrderMasterData();

        $quantity = 2;

        $response = $this->actingAs($user)->post(route('customers.orders.store', $customer),
            $this->makeOrderPayload($customer, $plan, $product, $quantity));

        $response->assertRedirect(route('customers.orders.index', $customer));
        $this->assertDatabaseHas('orders', [
            'customer_id' => $customer->id,
            'plan_id' => $plan->id,
            'product_id' => $product->id,
            'quantity' => $quantity,
            'unit_price' => $planProductPrice->price,
            'subtotal_amount' => $planProductPrice->price * $quantity,
            'order_type' => OrderType::INITIAL->value,
            'order_status' => OrderStatus::RECEIVED->value,
            'shipping_company' => 'ヤマト運輸',
            'remarks' => null,
            'order_date' => now()->toDateString(),
            'scheduled_delivery_date' => Carbon::now()->addDays(10)->toDateString(),
            'scheduled_shipping_date' => Carbon::now()->addDays(7)->toDateString(),
        ]);

        $order = Order::firstOrFail();

        $this->assertDatabaseHas('order_histories', [
            'customer_id' => $customer->id,
            'order_id' => $order->id,
            'user_id' => $user->id,
            'order_code_snapshot' => $order->order_code,
            'action_type' => OrderHistoryActionType::CREATE->value,
            'action_summary' => '受注情報を登録しました。',
        ]);

        $orderHistory = OrderHistory::firstOrFail();
        $beforeValues = $orderHistory->before_values;
        $afterValues = $orderHistory->after_values;

        $this->assertNull($beforeValues);
        $this->assertEquals($product->id, $afterValues['product_id']);
        $this->assertEquals($plan->id, $afterValues['plan_id']);
        $this->assertEquals($quantity, $afterValues['quantity']);
        $this->assertEquals(OrderStatus::RECEIVED->value, $afterValues['order_status']);
        $this->assertEquals(Carbon::now()->addDays(10)->toDateString(), $afterValues['scheduled_delivery_date']);
    }

    public function test_viewer_cannot_store_order()
    {
        $user = $this->makeUser(Role::VIEWER);

        ['customer' => $customer, 'plan' => $plan, 'product' => $product] = $this->prepareOrderMasterData();

        $quantity = 2;

        $response = $this->actingAs($user)->post(route('customers.orders.store', $customer),
            $this->makeOrderPayload($customer, $plan, $product, $quantity));

        $response->assertForbidden();
        $this->assertDatabaseCount('orders', 0);
    }

    public function test_guest_cannot_store_order()
    {
        ['customer' => $customer, 'plan' => $plan, 'product' => $product] = $this->prepareOrderMasterData();

        $quantity = 2;

        $response = $this->post(route('customers.orders.store', $customer),
            $this->makeOrderPayload($customer, $plan, $product, $quantity));

        $response->assertRedirect(route('login'));
        $this->assertDatabaseCount('orders', 0);
    }

    public function test_cannot_store_order_when_quantity_is_invalid()
    {
        $user = $this->makeUser(Role::ADMIN);

        ['customer' => $customer, 'plan' => $plan, 'product' => $product] = $this->prepareOrderMasterData();


        $quantity = 0;

        $response = $this->from(route('customers.orders.create', $customer))->actingAs($user)->post(route('customers.orders.store', $customer),
            $this->makeOrderPayload($customer, $plan, $product, $quantity));

        $response->assertRedirect(route('customers.orders.create', $customer));
        $response->assertSessionHasErrors('quantity');
        $this->assertDatabaseCount('orders', 0);
    }

    public function test_cannot_store_order_when_scheduled_delivery_date_is_invalid()
    {
        $user = $this->makeUser(Role::ADMIN);

        ['customer' => $customer, 'plan' => $plan, 'product' => $product] = $this->prepareOrderMasterData();


        $quantity = 2;

        $response = $this->from(route('customers.orders.create', $customer))->actingAs($user)->post(route('customers.orders.store', $customer),
            array_merge($this->makeOrderPayload($customer, $plan, $product, $quantity), [
                'scheduled_delivery_date' => now()->addDays(2)->toDateString(),
            ]));

        $response->assertRedirect(route('customers.orders.create', $customer));
        $response->assertSessionHasErrors('scheduled_delivery_date');
        $this->assertDatabaseCount('orders', 0);
    }

    private function makeUser(Role $role): User
    {
        return User::factory()->create([
            'role' => $role->value,
        ]);
    }

    private function prepareOrderMasterData(): array
    {
        $customer = Customer::factory()->create();
        $product = Product::factory()->water()->create();
        $plan = Plan::factory()->create();
        $planProductPrice = PlanProductPrice::factory()
            ->forPlan($plan)
            ->forProduct($product)
            ->create([
                'price' => 3000
        ]);

        return compact('customer', 'product', 'plan', 'planProductPrice');
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
}
