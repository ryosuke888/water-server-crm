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
        $standardPlan = Plan::where('plan_code', 'PLAN001')->firstOrFail();
        $familyPlan = Plan::where('plan_code', 'PLAN002')->firstOrFail();
        $premiumPlan = Plan::where('plan_code', 'PLAN003')->firstOrFail();

        $standardServer = Product::where('product_code', 'SRV001')->firstOrFail();
        $stylishServer = Product::where('product_code', 'SRV002')->firstOrFail();
        $naturalWater = Product::where('product_code', 'WTR001')->firstOrFail();
        $naturalMineralWater = Product::where('product_code', 'WTR002')->firstOrFail();

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
