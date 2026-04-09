<?php

namespace Database\Factories;

use App\Models\Plan;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Plan>
 */
class PlanFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'plan_code' => 'PLAN' . $this->fakeNumberMaker(),
            'name' => fake()->word(),
            'is_active' => true,
            'remarks' => fake()->word(),
        ];
    }

    private function fakeNumberMaker(): string
    {
        return str_pad($this->faker->unique()->numberBetween(100, 999), 3, '0', STR_PAD_LEFT);
    }
}
