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
        Schema::create('customers', function (Blueprint $table) {
            $table->id(); // 主キー

            $table->string('customer_code', 20)->unique(); // 顧客ID
            $table->string('name', 100); // 顧客名
            $table->index('name');
            $table->string('name_kana', 100)->nullable(); // 顧客名カナ

            $table->string('phone_number', 15); // 電話番号
            $table->index('phone_number');
            $table->string('email', 255)->nullable(); // メール

            // 契約先住所
            $table->string('postal_code', 8)->nullable(); // 郵便番号
            $table->string('prefecture', 20)->nullable(); // 都道府県
            $table->string('city', 100)->nullable(); // 市区町村
            $table->string('address_line1', 255)->nullable(); // 番地
            $table->string('address_line2', 255)->nullable(); // 建物名

            // 配送先情報
            $table->string('shipping_name')->nullable()->comment('配送先氏名');
            $table->string('shipping_postal_code', 10)->nullable()->comment('配送先郵便番号');
            $table->string('shipping_prefecture')->nullable()->comment('配送先都道府県');
            $table->string('shipping_city')->nullable()->comment('配送先市区町村');
            $table->string('shipping_address_line1')->nullable()->comment('配送先番地');
            $table->string('shipping_address_line2')->nullable()->comment('配送先建物名・部屋番号');

            $table->enum('contract_status', ['未契約', '契約中', '解約済'])->default('未契約'); // 契約状況

            $table->text('remarks')->nullable(); // 備考

            $table->timestamps(); // 作成日・更新日
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
