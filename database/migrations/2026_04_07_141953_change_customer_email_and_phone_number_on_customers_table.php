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
        Schema::table('customers', function (Blueprint $table) {
            $table->string('phone_number', 15)->unique()->comment('電話番号')->change();
            $table->string('email', 255)->nullable()->unique()->comment('メールアドレス')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->dropUnique(['phone_number']);
            $table->dropUnique(['email']);
            $table->string('phone_number', 15)->change();
            $table->string('email', 255)->nullable()->change();
            $table->index('phone_number');
            $table->index('email');
        });
    }
};
