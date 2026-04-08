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
        Schema::table('order_histories', function (Blueprint $table) {
            $table->string('action_type')->comment('対応種別')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('order_histories', function (Blueprint $table) {
            $table->enum('action_type', [
                'create',
                'update',
                'cancel',
                'status_change',
            ])->comment('対応種別')->change();
        });
    }
};
