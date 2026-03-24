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
            $table->foreignId('plan_code')->constrained()->cascadeOnDelete();  // プランコード
            $table->foreignId('product_code')->constrained()->cascadeOnDelete();  // 商品コード
            $table->integer('price');  // 価格
            $table->unique(['plan_code', 'product_code']);  // 複合ユニーク制約
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
