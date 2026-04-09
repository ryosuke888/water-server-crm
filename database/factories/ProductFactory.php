<?php

namespace Database\Factories;

use App\Enums\ProductCategory;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'product_code' => 'PRO' . $this->fakeNumberMaker(),
            'name' => fake()->word(),
            'category' => ProductCategory::WATER,
            'is_active' => true,
        ];
    }

    public function water()
    {
        return $this->state(fn () => [
            'product_code' => 'WTR' . $this->fakeNumberMaker(),
            'name' => 'ナチュラルウォーター 12L',
            'category' => ProductCategory::WATER,
        ]);
    }

     public function server()
    {
        return $this->state(fn () => [
            'product_code' => 'SER' . $this->fakeNumberMaker(),
            'name' => 'スタンダードサーバー',
            'category' => ProductCategory::SERVER,
        ]);
    }

    private function fakeNumberMaker(): string
    {
        return str_pad($this->faker->unique()->numberBetween(100, 999), 3, '0', STR_PAD_LEFT);
    }
}
