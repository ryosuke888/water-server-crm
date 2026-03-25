<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;


class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $collection = collect([
            [
                'product_code' => 'SRV001',
                'name' => 'スタンダードサーバー',
                'category' => 'server',
                'is_active' => true,
            ],
            [
                'product_code' => 'SRV002',
                'name' => 'スタイリッシュサーバー',
                'category' => 'server',
                'is_active' => true,

            ],
            [
                'product_code' => 'WTR001',
                'name' => 'ナチュラルウォーター 12L',
                'category' => 'water',
                'is_active' => true,
            ],
            [
                'product_code' => 'WTR002',
                'name' => 'ナチュラルミネラルウォーター 12L',
                'category' => 'water',
                'is_active' => true,
            ],
        ]);

        $collection->each(function ($product) {
            Product::create($product);
        });
    }
}
