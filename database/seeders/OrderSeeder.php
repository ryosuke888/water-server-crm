<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\Order;
use App\Models\Plan;
use App\Models\PlanProductPrice;
use App\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $customer = Customer::firstOrFail();

        $products = Product::whereIn('product_code', ['SRV001', 'WTR001'])->get()->keyBy('product_code');

        $server = $products['SRV001'];
        $water = $products['WTR001'];

        $plan = Plan::where('plan_code', 'PLAN003')->firstOrFail();

        $planProductPrices = $plan->planProductPrices()->whereIn('product_id', [$server->id, $water->id])->get()->keyBy('product_id');

        $serverPrice = $planProductPrices[$server->id];
        $waterPrice = $planProductPrices[$water->id];

        $collection = collect([
            [
                'order_code' => 'ORD000001',
                'customer_id' => $customer->id,
                'product_id' => $server->id,
                'plan_id' => $plan->id,
                'order_type' => 'initial',
                'quantity' => 1,
                'unit_price' => $serverPrice->price,
                'subtotal_amount' => 1 * $serverPrice->price,
                'order_status' => '受付',
                'order_date' => now()->toDateString(),
                'scheduled_shipping_date' => now()->addDay()->toDateString(),
                'scheduled_delivery_date' => now()->addDays(3)->toDateString(),
                'shipping_company' => 'ヤマト運輸',
                'tracking_number' => null,
                'shipping_status' => '未連携',
                'api_synced_at' => null,
                'remarks' => '初回サーバー配送',
            ],
            [
                'order_code' => 'ORD000002',
                'customer_id' => $customer->id,
                'product_id' => $water->id,
                'plan_id' => $plan->id,
                'order_type' => 'regular',
                'quantity' => 2,
                'unit_price' => $waterPrice->price,
                'subtotal_amount' => 2 * $waterPrice->price,
                'order_status' => '受付',
                'order_date' => now()->toDateString(),
                'scheduled_shipping_date' => now()->addDays(7)->toDateString(),
                'scheduled_delivery_date' => now()->addDays(10)->toDateString(),
                'shipping_company' => 'ヤマト運輸',
                'tracking_number' => null,
                'shipping_status' => '未連携',
                'api_synced_at' => null,
                'remarks' => '定期配送の水',
            ],
        ]);

        $collection->each(function($order) {
            Order::create($order);
        });


    }
}
