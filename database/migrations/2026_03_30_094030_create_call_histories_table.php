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
        Schema::create('call_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignID('customer_id')->nullable()->constrained()->nullOnDelete()->comment('顧客ID');
            $table->foreignID('user_id')->nullable()->constrained()->nullOnDelete()->comment('ユーザーID');

            $table->string('call_type')->comment('対応種別');
            $table->string('channel')->default('phone')->comment('対応チャネル');
            $table->text('call_summary')->comment('対応内容');
            $table->string('call_result')->nullable()->comment('対応結果');
            $table->boolean('needs_follow_up')->default(false)->comment('要再対応');
            $table->date('follow_up_date')->nullable()->comment('次回対応予定日');
            $table->timestamp('called_at')->comment('対応日時');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('call_histories');
    }
};
