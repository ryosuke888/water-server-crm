<?php

namespace Tests\Feature\customers;

use App\Enums\CustomerContractStatus;
use App\Enums\Role;
use App\Models\Customer;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class CustomerImportTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_import_customer()
    {
        $user = User::factory()->create([
            'role' => Role::ADMIN,
        ]);

        $header = 'name,phone_number,email,postal_code,prefecture,city,address_line1,address_line2,shipping_name,shipping_postal_code,shipping_prefecture,shipping_city,shipping_address_line1,shipping_address_line2,contract_status,remarks';
        $row = '山田太郎,09012345678,test1@example.com,1234567,東京都,新宿区,1-1-1,,山田太郎,1234567,東京都,新宿区,1-1-1,,PROSPECT,テスト備考';
        $content = $header . "\n" . $row;
        $csvFile = UploadedFile::fake()->createWithContent('customers.csv', $content);

        $response = $this->from(route('customers.import.create'))->actingAs($user)->post(route('customers.import.store'), [
            'csv_file' => $csvFile,
        ]);

        $response->assertRedirect(route('customers.index'));

        $this->assertDatabaseHas('customers', [
            'name' => '山田太郎',
            'phone_number' => '09012345678',
            'email' => 'test1@example.com',
            'postal_code' => '1234567',
            'prefecture' => '東京都',
            'city' => '新宿区',
            'address_line1' => '1-1-1',
            'shipping_name' => '山田太郎',
            'shipping_postal_code' => '1234567',
            'shipping_prefecture' => '東京都',
            'shipping_city' => '新宿区',
            'shipping_address_line1' => '1-1-1',
            'contract_status' => CustomerContractStatus::PROSPECT->value,
            'remarks' => 'テスト備考',
        ]);

        $customer = Customer::firstOrFail();

        $this->assertNull($customer->address_line2);
        $this->assertNull($customer->shipping_address_line2);
    }
}
