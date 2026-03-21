<?php

namespace Database\Seeders;

use App\Models\Customer;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Customer::create([
            'customer_name' => '山田太郎',
            'customer_name_kana' => 'ヤマダタロウ',
            'phone_number' => '09012345678',
            'email' => 'yamada@example.com',
            'postal_code' => '1000001',
            'prefecture' => '東京都',
            'city' => '千代田区',
            'address_line1' => '1-1-1',
            'address_line2' => 'テストビル101',
            'contract_status' => '契約中',
            'remarks' => '初回顧客',
        ]);

        Customer::create([
            'customer_name' => '佐藤花子',
            'customer_name_kana' => 'サトウハナコ',
            'phone_number' => '08098765432',
            'email' => 'sato@example.com',
            'postal_code' => '1500001',
            'prefecture' => '東京都',
            'city' => '渋谷区',
            'address_line1' => '2-2-2',
            'address_line2' => null,
            'contract_status' => '未契約',
            'remarks' => null,
        ]);
    }
}
