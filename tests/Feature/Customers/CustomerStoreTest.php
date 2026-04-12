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
        $user = $this->makeUser(Role::ADMIN);

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
        $user = $this->makeUser(Role::VIEWER);

        $customer = Customer::factory()->make();

        $response = $this->actingAs($user)->post(route('customers.store'),
            $this->makeCustomerPayload($customer));

        $response->assertForbidden();
        $this->assertDatabaseCount('customers', 0);
    }

    public function test_guest_cannot_store_customer()
    {
        $customer = Customer::factory()->make();

        $response = $this->post(route('customers.store'),
            $this->makeCustomerPayload($customer));

        $response->assertRedirect(route('login'));
        $this->assertDatabaseCount('customers', 0);
    }

    public function test_cannot_store_customer_when_name_is_empty()
    {
        $user = $this->makeUser(Role::ADMIN);

        $customer = Customer::factory()->make();

        $customer->name = '';

        $response = $this->from(route('customers.create'))->actingAs($user)->post(route('customers.store'),
            $this->makeCustomerPayload($customer));

        $response->assertRedirect(route('customers.create'));
        $response->assertSessionHasErrors('name');
        $this->assertDatabaseCount('customers', 0);
    }

    public function test_cannot_store_customer_when_phone_number_is_empty()
    {
        $user = $this->makeUser(Role::ADMIN);

        $customer = Customer::factory()->make();

        $customer->phone_number = '';

        $response = $this->from(route('customers.create'))->actingAs($user)->post(route('customers.store'),
            $this->makeCustomerPayload($customer));

        $response->assertRedirect(route('customers.create'));
        $response->assertSessionHasErrors('phone_number');
        $this->assertDatabaseCount('customers', 0);
    }

    public function test_cannot_store_customer_when_email_is_invalid()
    {
        $user = $this->makeUser(Role::ADMIN);

        $customer = Customer::factory()->make();

        $customer->email = 'invalid-email';

        $response = $this->from(route('customers.create'))->actingAs($user)->post(route('customers.store'),
            $this->makeCustomerPayload($customer));

        $response->assertRedirect(route('customers.create'));
        $response->assertSessionHasErrors('email');
        $this->assertDatabaseCount('customers', 0);
    }

    public function test_cannot_store_customer_when_phone_number_invalid()
    {
        $user = $this->makeUser(Role::ADMIN);

        $customer = Customer::factory()->make();

        $customer->phone_number = '1234567';

        $response = $this->from(route('customers.create'))->actingAs($user)->post(route('customers.store'),
            $this->makeCustomerPayload($customer));

        $response->assertRedirect(route('customers.create'));
        $response->assertSessionHasErrors('phone_number');
        $this->assertDatabaseCount('customers', 0);
    }

    public function test_cannot_store_customer_when_postal_code_invalid()
    {
        $user = $this->makeUser(Role::ADMIN);

        $customer = Customer::factory()->make();

        $customer->postal_code = '12345678';

        $response = $this->from(route('customers.create'))->actingAs($user)->post(route('customers.store'),
            $this->makeCustomerPayload($customer));

        $response->assertRedirect(route('customers.create'));
        $response->assertSessionHasErrors('postal_code');
        $this->assertDatabaseCount('customers', 0);
    }

    private function makeUser(Role $role): User
    {
        return User::factory()->create([
            'role' => $role->value,
        ]);
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
