<?php

namespace Database\Factories;

use App\Models\Plan;
use App\Models\PlanProductPrice;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<PlanProductPrice>
 */
class PlanProductPriceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'plan_id' => Plan::factory(),
            'product_id' => Product::factory(),
            'price' => $this->faker->numberBetween(1000, 5000),
        ];
    }

    public function forPlan(Plan $plan)
    {
        return $this->state(fn () => [
            'plan_id' => $plan->id,
        ]);
    }

    public function forProduct(Product $product)
    {
        return $this->state(fn () => [
            'product_id' => $product->id,
        ]);
    }
}
