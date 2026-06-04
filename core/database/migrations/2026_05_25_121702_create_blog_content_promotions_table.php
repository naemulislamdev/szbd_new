<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations. php artisan migrate --path=database/migrations/2026_05_25_121702_create_blog_content_promotions_table.php
     */
    public function up(): void
    {
        Schema::create('blog_content_promotions', function (Blueprint $table) {
            $table->id();
            $table->string("add_img")->nullable();
            $table->string("add_url")->nullable();
            $table->json("products")->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('blog_content_promotions');
    }
};
