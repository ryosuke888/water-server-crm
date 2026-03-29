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
        Schema::create('order_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->nullable()->constrained()->nullOnDelete()->comment('顧客ID');
            $table->foreignId('order_id')->nullable()->constrained()->nullOnDelete()->comment('受注ID');
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete()->comment('対応者ID');
            $table->string('order_code_snapshot')->nullable()->comment('受注番号スナップショット');
            $table->enum('action_type', [
                'create',
                'update',
                'cancel',
                'status_change',
            ])->comment('対応種別');
            $table->text('action_summary')->comment('対応概要');
            $table->json('before_values')->nullable()->comment('変更前');
            $table->json('after_values')->nullable()->comment('変更後');
            $table->timestamp('acted_at')->nullable()->comment('対応日時');
            $table->timestamps();

            $table->index(['customer_id', 'order_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_histories');
    }
};
