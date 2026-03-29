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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_code')->unique()->nullable()->comment('受注番号');
            $table->foreignId('customer_id')->constrained()->cascadeOnDelete()->comment('顧客ID');
            $table->foreignId('plan_id')->nullable()->constrained()->nullOnDelete()->comment('プランID');
            $table->foreignId('product_id')->constrained()->restrictOnDelete()->comment('商品ID');
            $table->unsignedBigInteger('quantity')->nullable()->default(1)->comment('数量');
            $table->integer('unit_price')->nullable()->comment('単価');
            $table->integer('subtotal_amount')->nullable()->comment('小計');
            $table->string('order_type')->comment('受注種別'); // 初回、変更、、定期配送
            $table->string('order_status')->default('受付')->comment('受注ステータス'); //受付、出荷準備中、キャンセル、完了
            $table->date('order_date')->nullable()->comment('注文日');
            $table->date('scheduled_shipping_date')->nullable()->comment('出荷予定日');
            $table->date('scheduled_delivery_date')->nullable()->comment('配送予定日');
            $table->string('shipping_company')->nullable()->comment('配送会社');
            $table->string('tracking_number')->nullable()->comment('追跡番号');
            $table->string('shipping_status')->nullable()->comment('配送ステータス'); //API連携先の配送状態
            $table->timestamp('api_synced_at')->nullable()->comment('API連携日時');
            $table->text('remarks')->nullable()->comment('備考');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
