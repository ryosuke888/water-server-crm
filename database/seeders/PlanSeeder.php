<?php

namespace Database\Seeders;

use App\Models\Plan;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;



class PlanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $collection = collect([
            [
                'plan_code' => 'PLAN001',
                'name' => 'スタンダードプラン',
                'is_active' => true,
                'remarks' => '契約3年',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'plan_code' => 'PLAN002',
                'name' => 'ファミリープラン',
                'is_active' => true,
                'remarks' => '家族割プラン',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'plan_code' => 'PLAN003',
                'name' => 'プレミアムプラン',
                'is_active' => true,
                'remarks' => '契約5年',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        $collection->each(function($plan) {
            Plan::create($plan);
        });
    }
}
