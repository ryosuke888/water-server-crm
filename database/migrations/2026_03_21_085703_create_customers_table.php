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

            $table->string('customer_name', 100); // 顧客名
            $table->index('customer_name');
            $table->string('customer_name_kana', 100)->nullable(); // 顧客名カナ

            $table->string('phone_number', 15); // 電話番号
            $table->index('phone_number');
            $table->string('email', 255)->nullable(); // メール

            $table->string('postal_code', 8); // 郵便番号
            $table->string('prefecture', 20); // 都道府県
            $table->string('city', 100); // 市区町村
            $table->string('address_line1', 255)->nullable(); // 番地
            $table->string('address_line2', 255)->nullable(); // 建物名

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
