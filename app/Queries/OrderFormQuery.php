<?php
namespace App\Queries;

use App\Models\Plan;
use App\Models\PlanProductPrice;
use App\Models\Product;

class OrderFormQuery {
    public static function MasterData(): array
    {
        $plans = Plan::where('is_active', true)->get();
        $products = Product::where('is_active', true)->get();
        $planProductPrices = PlanProductPrice::with('plans', 'products')->get();

        return compact('plans', 'products', 'planProductPrices');
    }
}
