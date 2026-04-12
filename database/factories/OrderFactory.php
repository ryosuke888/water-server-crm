<?php

namespace Database\Factories;

use App\Enums\OrderStatus;
use App\Enums\OrderType;
use App\Models\Customer;
use App\Models\Order;
use App\Models\Plan;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

/**
 * @extends Factory<Order>
 */
class OrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'order_code'  => 'O' . str_pad(fake()->unique()->numberBetween(1, 9999999), 7, '0', STR_PAD_LEFT),
            'customer_id' => Customer::factory(),
            'product_id' => Product::factory(),
            'plan_id' => Plan::factory(),
            'order_type' => OrderType::INITIAL,
            'quantity' => 2,
            'order_status' => OrderStatus::RECEIVED,
            'scheduled_shipping_date' => Carbon::now()->addDays(7)->toDateString(),  //出荷日
            'scheduled_delivery_date' => Carbon::now()->addDays(10)->toDateString(),  //配送日
            'shipping_company' => 'ヤマト運輸',
            'remarks' => null,
        ];
    }
}
