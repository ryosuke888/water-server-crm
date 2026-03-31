<?php

namespace Database\Factories;

use App\Models\Customer;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Customer>
 */
class CustomerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'customer_code' => 'C' . str_pad(fake()->unique()->numberBetween(1, 9999999), 7, '0', STR_PAD_LEFT),
            'name' => fake()->name(),
            'name_kana' => mb_convert_kana(fake()->name(), 'K'),
            'phone_number' => '080' . fake()->numerify('########'),
            'email' => fake()->unique()->safeEmail(),
            'contract_status' => '未契約',

            'postal_code' =>  fake()->numerify('#######'),
            'prefecture' => fake()->randomElement([
                '東京都','大阪府','神奈川県','愛知県','福岡県'
            ]),
            'city' => fake()->city(),
            'address_line1' => fake()->streetAddress(),
            'address_line2' => fake()->secondaryAddress(),
        ];
    }
}
