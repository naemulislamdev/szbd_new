<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('trending_keywords', function (Blueprint $table) {
            $table->id();
            $table->string('keyword');
            $table->integer('sort_order')->default(0);
            $table->tinyInteger('is_active')->default(1);
            $table->timestamps();
        });

        $seed = ['Saree','Three Piece','Shrug','Abaya','Co-ord Set','Kurti','Panjabi','Borka'];
        foreach ($seed as $i => $word) {
            DB::table('trending_keywords')->insert([
                'keyword'    => $word,
                'sort_order' => $i,
                'is_active'  => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('trending_keywords');
    }
};
