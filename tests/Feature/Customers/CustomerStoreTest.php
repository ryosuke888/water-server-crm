<?php

namespace Tests\Feature\Customers;

use App\Enums\Role;
use App\Models\Customer;
use App\Models\Order;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CustomerStoreTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_store_customer()
    {
        $user = User::factory()->create([
            'role' => Role::ADMIN->value,
        ]);

        $customer = Customer::factory()->make();

        $response = $this->actingAs($user)->post(route('customers.store'),
            $this->makeCustomerPayload($customer));

        $this->assertDatabaseHas('customers', [
            'name' => $customer->name,
            'phone_number' => $customer->phone_number,
            'email' => $customer->email,
            'postal_code' => $customer->postal_code,
            'prefecture' => $customer->prefecture,
            'city' => $customer->city,
            'address_line1' => $customer->address_line1,
            'address_line2' => $customer->address_line2,
            'contract_status' => $customer->contract_status->value,
        ]);

        $customer = Customer::latest('id')->firstOrFail();

        $response->assertRedirect(route('customers.show', $customer));

        $this->assertNotNull($customer->customer_code);
    }

    public function test_viewer_cannot_store_customer()
    {
        $user = User::factory()->create([
            'role' => Role::VIEWER->value,
        ]);

        $customer = Customer::factory()->make();

        $response = $this->actingAs($user)->post(route('customers.store'),
            $this->makeCustomerPayload($customer));

        $response->assertForbidden();
        $this->assertDatabaseCount('customers', 0);
    }

    private function makeCustomerPayload(Customer $customer): array
    {
        return  [
            'name' => $customer->name,
            'phone_number' => $customer->phone_number,
            'email' => $customer->email,
            'postal_code' => $customer->postal_code,
            'prefecture' => $customer->prefecture,
            'city' => $customer->city,
            'address_line1' => $customer->address_line1,
            'address_line2' => $customer->address_line2,
            'contract_status' => $customer->contract_status->value,
        ];
    }
}
