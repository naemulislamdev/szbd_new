<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * php artisan migrate --path=core/database/migrations/2026_06_07_125404_create_shipping_configs_table.php
     */
    public function up(): void
    {
        Schema::create('shipping_configs', function (Blueprint $table) {
            $table->id();
            $table->enum('shipping_type', [
                'free_shipping',
                'order_wise',
                'category_wise',
                'product_wise'
            ])->default('order_wise');
            $table->enum('free_shipping_type', [
                'all_products',
                'without_discount_product'
            ])->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shipping_configs');
    }
};
