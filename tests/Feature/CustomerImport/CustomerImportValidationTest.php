<?php

namespace Tests\Feature\CustomerImport;

use App\Enums\Role;
use App\Models\Customer;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class CustomerImportValidationTest extends TestCase
{
    use RefreshDatabase;

    private const CSV_HEADER = 'name,phone_number,email,postal_code,prefecture,city,address_line1,address_line2,shipping_name,shipping_postal_code,shipping_prefecture,shipping_city,shipping_address_line1,shipping_address_line2,contract_status,remarks';
    private const INVALID_CSV_HEADER = 'name,phone,email,postal_code,prefecture,city,address_line1,address_line2,shipping_name,shipping_postal_code,shipping_prefecture,shipping_city,shipping_address_line1,shipping_address_line2,contract_status,remarks';
    private const CSV_ROW_BASIC_EXAMPLE = '山田太郎,09012345678,test1@example.com,1234567,東京都,新宿区,1-1-1,,山田太郎,1234567,東京都,新宿区,1-1-1,,PROSPECT,テスト備考';

    public function test_cannot_import_customer_when_header_is_invalid()
    {
        $user = User::factory()->create([
            'role' => Role::ADMIN->value,
        ]);

        $csvFile = $this->makeCsvInvalidHeaderFile();

        $response = $this->from(route('customers.import.create'))->actingAs($user)->post(route('customers.import.store'), [
            'csv_file' => $csvFile,
        ]);

        $response->assertRedirect(route('customers.import.create'));
        $response->assertSessionHasErrors(['csv_file']);
        $this->assertDatabaseCount('customers', 0);
    }

    public function test_cannot_import_customer_when_name_is_empty()
    {
        $user = User::factory()->create([
            'role' => Role::ADMIN->value,
        ]);

        $csvFile = $this->makeCsvEmptyNameFile();

        $response = $this->from(route('customers.import.create'))->actingAs($user)->post(route('customers.import.store'), [
            'csv_file' => $csvFile,
        ]);

        $response->assertRedirect(route('customers.import.create'));
        $response->assertSessionHasErrors(['csv_file']);
        $this->assertDatabaseCount('customers', 0);
    }

    public function test_cannot_import_customer_when_phone_number_is_invalid()
    {
        $user = User::factory()->create([
            'role' => Role::ADMIN->value,
        ]);

        $csvFile = $this->makeCsvInvalidPhoneNumberFile();

        $response = $this->from(route('customers.import.create'))->actingAs($user)->post(route('customers.import.store'), [
            'csv_file' => $csvFile,
        ]);

        $response->assertRedirect(route('customers.import.create'));
        $response->assertSessionHasErrors(['csv_file']);
        $this->assertDatabaseCount('customers', 0);
    }

    public function test_cannot_import_customer_when_contract_status_is_invalid()
    {
        $user = User::factory()->create([
            'role' => Role::ADMIN->value,
        ]);

        $csvFile = $this->makeCsvInvalidContractStatusFile();

        $response = $this->from(route('customers.import.create'))->actingAs($user)->post(route('customers.import.store'), [
            'csv_file' => $csvFile,
        ]);

        $response->assertRedirect(route('customers.import.create'));
        $response->assertSessionHasErrors(['csv_file']);
        $this->assertDatabaseCount('customers', 0);
    }

    public function test_cannot_import_customer_when_csv_is_empty()
    {
        $user = User::factory()->create([
            'role' => Role::ADMIN->value,
        ]);

        $csvFile = $this->makeEmptyCsvFile();

        $response = $this->from(route('customers.import.create'))->actingAs($user)->post(route('customers.import.store'), [
            'csv_file' => $csvFile,
        ]);

        $response->assertRedirect(route('customers.import.create'));
        $response->assertSessionHasErrors(['csv_file']);
        $this->assertDatabaseCount('customers', 0);
    }

    public function test_cannot_import_customer_when_header_exists_and_csv_content_is_empty()
    {
        $user = User::factory()->create([
            'role' => Role::ADMIN->value,
        ]);

        $csvFile = $this->makeEmptyContentCsvFile();

        $response = $this->from(route('customers.import.create'))->actingAs($user)->post(route('customers.import.store'), [
            'csv_file' => $csvFile,
        ]);

        $response->assertRedirect(route('customers.import.create'));
        $response->assertSessionHasErrors(['csv_file']);
        $this->assertDatabaseCount('customers', 0);
    }

    public function test_cannot_import_customer_when_same_phone_number_is_in_another_row()
    {
        $user = User::factory()->create([
            'role' => Role::ADMIN->value,
        ]);

        $csvFile = $this->makeCsvSamePhoneNumberInDataFile();

        $response = $this->from(route('customers.import.create'))->actingAs($user)->post(route('customers.import.store'), [
            'csv_file' => $csvFile,
        ]);

        $response->assertRedirect(route('customers.import.create'));
        $response->assertSessionHasErrors(['csv_file']);
        $this->assertDatabaseCount('customers', 0);
    }

    public function test_cannot_import_customer_when_same_email_is_in_another_row()
    {
        $user = User::factory()->create([
            'role' => Role::ADMIN->value,
        ]);

        $csvFile = $this->makeCsvSameEmailInDataFile();

        $response = $this->from(route('customers.import.create'))->actingAs($user)->post(route('customers.import.store'), [
            'csv_file' => $csvFile,
        ]);

        $response->assertRedirect(route('customers.import.create'));
        $response->assertSessionHasErrors(['csv_file']);
        $this->assertDatabaseCount('customers', 0);
    }

    public function test_cannot_import_customer_when_same_phone_number_is_in_database()
    {
        $user = User::factory()->create([
            'role' => Role::ADMIN->value,
        ]);

        Customer::factory()->create([
            'phone_number' => '09012345678',
        ]);

        $csvFile = $this->makeCsvFile();

        $response = $this->from(route('customers.import.create'))->actingAs($user)->post(route('customers.import.store'), [
            'csv_file' => $csvFile,
        ]);

        $response->assertRedirect(route('customers.import.create'));
        $response->assertSessionHasErrors(['csv_file']);
    }

    public function test_cannot_import_customer_when_same_email_is_in_database()
    {
        $user = User::factory()->create([
            'role' => Role::ADMIN->value,
        ]);

        Customer::factory()->create([
            'email' => 'test1@example.com',
        ]);

        $csvFile = $this->makeCsvFile();

        $response = $this->from(route('customers.import.create'))->actingAs($user)->post(route('customers.import.store'), [
            'csv_file' => $csvFile,
        ]);

        $response->assertRedirect(route('customers.import.create'));
        $response->assertSessionHasErrors(['csv_file']);
    }

    public function test_cannot_import_customer_when_column_count_mismatch()
    {
        $user = User::factory()->create([
            'role' => Role::ADMIN->value,
        ]);

        $csvFile = $this->makeCsvColumnCountMismatchFile();

        $response = $this->from(route('customers.import.create'))->actingAs($user)->post(route('customers.import.store'), [
            'csv_file' => $csvFile,
        ]);

        $response->assertRedirect(route('customers.import.create'));
        $response->assertSessionHasErrors(['csv_file']);
        $this->assertDatabaseCount('customers', 0);
    }

    private function makeCsvFile(): UploadedFile
    {
        $row = '山田太郎,09012345678,test1@example.com,1234567,東京都,新宿区,1-1-1,,山田太郎,1234567,東京都,新宿区,1-1-1,,PROSPECT,テスト備考';
        $content = self::CSV_HEADER . "\n" . $row;
        return UploadedFile::fake()->createWithContent('customers.csv', $content);
    }

    private function makeEmptyCsvFile(): UploadedFile
    {
        return UploadedFile::fake()->createWithContent('customers.csv', '');
    }

    private function makeEmptyContentCsvFile(): UploadedFile
    {
        return UploadedFile::fake()->createWithContent('customers.csv', self::CSV_HEADER);
    }

    private function makeCsvInvalidHeaderFile(): UploadedFile
    {
        $content = self::INVALID_CSV_HEADER . "\n" . self::CSV_ROW_BASIC_EXAMPLE;
        return UploadedFile::fake()->createWithContent('customers.csv', $content);
    }

    private function makeCsvEmptyNameFile(): UploadedFile
    {
        $row = ',09012345678,test1@example.com,1234567,東京都,新宿区,1-1-1,,山田太郎,1234567,東京都,新宿区,1-1-1,,PROSPECT,テスト備考';
        $content = self::CSV_HEADER . "\n" . $row;
        return UploadedFile::fake()->createWithContent('customers.csv', $content);
    }

    private function makeCsvInvalidPhoneNumberFile(): UploadedFile
    {
        $row = '山田太郎,1234567,test1@example.com,1234567,東京都,新宿区,1-1-1,,山田太郎,1234567,東京都,新宿区,1-1-1,,PROSPECT,テスト備考';
        $content = self::CSV_HEADER . "\n" . $row;
        return UploadedFile::fake()->createWithContent('customers.csv', $content);
    }

    private function makeCsvInvalidContractStatusFile(): UploadedFile
    {
        $row = '山田太郎,1234567,test1@example.com,1234567,東京都,新宿区,1-1-1,,山田太郎,1234567,東京都,新宿区,1-1-1,,Invalid,テスト備考';
        $content = self::CSV_HEADER . "\n" . $row;
        return UploadedFile::fake()->createWithContent('customers.csv', $content);
    }

    private function makeCsvSamePhoneNumberInDataFile(): UploadedFile
    {
        $row = '山田太郎,09012345678,test2@example.com,1234567,東京都,新宿区,1-1-1,,山田太郎,1234567,東京都,新宿区,1-1-1,,PROSPECT,テスト備考';
        $content = self::CSV_HEADER . "\n" . self::CSV_ROW_BASIC_EXAMPLE . "\n" . $row;
        return UploadedFile::fake()->createWithContent('customers.csv', $content);
    }

    private function makeCsvSameEmailInDataFile(): UploadedFile
    {
        $row = '山田太郎,09012341234,test1@example.com,1234567,東京都,新宿区,1-1-1,,山田太郎,1234567,東京都,新宿区,1-1-1,,PROSPECT,テスト備考';
        $content = self::CSV_HEADER . "\n" . self::CSV_ROW_BASIC_EXAMPLE . "\n" . $row;
        return UploadedFile::fake()->createWithContent('customers.csv', $content);
    }

    private function makeCsvColumnCountMismatchFile(): UploadedFile
    {
        $row = '山田太郎,1234567,test1@example.com,1234567,東京都,新宿区,1-1-1,,山田太郎,1234567';
        $content = self::CSV_HEADER . "\n" . $row;
        return UploadedFile::fake()->createWithContent('customers.csv', $content);
    }
}
