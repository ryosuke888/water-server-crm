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
            $table->string('postal_code', 7)->nullable()->change();
            $table->string('shipping_postal_code', 7)->nullable()->comment('配送先郵便番号')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->string('postal_code', 8)->nullable()->change();
            $table->string('shipping_postal_code', 10)->nullable()->comment('配送先郵便番号')->change();
        });
    }
};
