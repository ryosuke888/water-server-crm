<?php

namespace Tests\Feature\CustomerImport;

use App\Enums\CustomerContractStatus;
use App\Enums\Role;
use App\Models\Customer;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class CustomerImportTest extends TestCase
{
    use RefreshDatabase;

    private const CSV_HEADER = 'name,phone_number,email,postal_code,prefecture,city,address_line1,address_line2,shipping_name,shipping_postal_code,shipping_prefecture,shipping_city,shipping_address_line1,shipping_address_line2,contract_status,remarks';
    private const INVALID_CSV_HEADER = 'name,phone,email,postal_code,prefecture,city,address_line1,address_line2,shipping_name,shipping_postal_code,shipping_prefecture,shipping_city,shipping_address_line1,shipping_address_line2,contract_status,remarks';

    public function test_admin_can_import_customer()
    {
        $user = User::factory()->create([
            'role' => Role::ADMIN->value,
        ]);

        $csvFile = $this->makeCsvFile();
        $response = $this->actingAs($user)->post(route('customers.import.store'), [
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
        $this->assertDatabaseCount('customers', 1);
    }

    public function test_viewer_cannot_import_customer()
    {
        $user = User::factory()->create([
            'role' => Role::VIEWER->value,
        ]);

        $csvFile = $this->makeCsvFile();
        $response = $this->actingAs($user)->post(route('customers.import.store'), [
            'csv_file' => $csvFile,
        ]);

        $response->assertForbidden();

        $this->assertDatabaseCount('customers', 0);
    }

    public function test_guest_cannot_import_customer()
    {
        $csvFile = $this->makeCsvFile();
        $response = $this->post(route('customers.import.store'), [
            'csv_file' => $csvFile,
        ]);

        $response->assertRedirect(route('login'));
        $this->assertDatabaseCount('customers', 0);
    }

    private function makeCsvFile(): UploadedFile
    {
        $row = '山田太郎,09012345678,test1@example.com,1234567,東京都,新宿区,1-1-1,,山田太郎,1234567,東京都,新宿区,1-1-1,,PROSPECT,テスト備考';
        $content = self::CSV_HEADER . "\n" . $row;
        return UploadedFile::fake()->createWithContent('customers.csv', $content);
    }


}
