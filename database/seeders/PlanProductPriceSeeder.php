<?php

namespace Database\Seeders;

use App\Models\Plan;
use App\Models\PlanProductPrice;
use App\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PlanProductPriceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $plans = Plan::whereIn('plan_code', ['PLAN001', 'PLAN002', 'PLAN003'])->get()->keyBy('plan_code');

        $standardPlan = $plans['PLAN001'];
        $familyPlan = $plans['PLAN002'];
        $premiumPlan = $plans['PLAN003'];

        $products = Product::whereIn('product_code', ['SRV001', 'SRV002', 'WTR001', 'WTR002'])->get()->keyBy('product_code');

        $standardServer = $products['SRV001'];
        $stylishServer = $products['SRV002'];
        $naturalWater = $products['WTR001'];
        $naturalMineralWater = $products['WTR002'];


        $collection = collect([
            [
                'plan_id' => $standardPlan->id,
                'product_id' => $standardServer->id,
                'price' => 0,
            ],
            [
                'plan_id' => $standardPlan->id,
                'product_id' => $stylishServer->id,
                'price' => 0,
            ],
            [
                'plan_id' => $standardPlan->id,
                'product_id' => $naturalWater->id,
                'price' => 1900,

            ],
            [
                'plan_id' => $standardPlan->id,
                'product_id' => $naturalMineralWater->id,
                'price' => 2000,
            ],
            [
                'plan_id' => $familyPlan->id,
                'product_id' => $standardServer->id,
                'price' => 0,
            ],
            [
                'plan_id' => $familyPlan->id,
                'product_id' => $stylishServer->id,
                'price' => 0,

            ],
            [
                'plan_id' => $familyPlan->id,
                'product_id' => $naturalWater->id,
                'price' => 1800,
            ],
            [
                'plan_id' => $familyPlan->id,
                'product_id' => $naturalMineralWater->id,
                'price' => 1900,
            ],
            [
                'plan_id' => $premiumPlan->id,
                'product_id' => $standardServer->id,
                'price' => 0,
            ],
            [
                'plan_id' => $premiumPlan->id,
                'product_id' => $stylishServer->id,
                'price' => 0,
            ],
            [
                'plan_id' => $premiumPlan->id,
                'product_id' => $naturalWater->id,
                'price' => 1800,
            ],
            [
                'plan_id' => $premiumPlan->id,
                'product_id' => $naturalMineralWater->id,
                'price' => 1900,
            ],
        ]);

        $collection->each(function ($price) {
            PlanProductPrice::create($price);
        });
    }
}
