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

    public function test_can_store_customer()
    {
        $user = User::factory()->create([
            'role' => Role::ADMIN,
        ]);

        $customer = Customer::factory()->make();

        $response = $this->actingAs($user)->post(route('customers.store'), [
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
}
