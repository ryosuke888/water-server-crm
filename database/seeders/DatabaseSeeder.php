<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\Order;
use App\Models\Plan;
use App\Models\PlanProductPrice;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'test',
            'email' => 'test@example.com',
            'password' => 'test',
        ]);

        $this->call(Customer::class);
        $this->call(Order::class);
        $this->call(PlanProductPrice::class);
        $this->call(Plan::class);
        $this->call(Product::class);
    }
}
