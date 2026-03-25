<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('plan_product_prices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('plan_id')->constrained()->cascadeOnDelete();  // プランID
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();  // 商品ID
            $table->integer('price');  // 価格
            $table->unique(['plan_id', 'product_id']);  // 複合ユニーク制約
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('plan_product_prices');
    }
};
