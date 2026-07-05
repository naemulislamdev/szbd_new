<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    // php artisan migrate --path=database/migrations/2026_06_19_000001_add_loyalty_tier_to_users_table.php
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Tier: New, Bronze, Silver, Gold, VIP
            $table->string('loyalty_tier')->default('New')->after('loyalty_point');
            $table->unsignedInteger('completed_orders_count')->default(0)->after('loyalty_tier');
            $table->decimal('total_completed_amount', 12, 2)->default(0)->after('completed_orders_count');
            $table->timestamp('loyalty_tier_updated_at')->nullable()->after('total_completed_amount');

            $table->index('loyalty_tier');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex(['loyalty_tier']);
            $table->dropColumn([
                'loyalty_tier',
                'completed_orders_count',
                'total_completed_amount',
                'loyalty_tier_updated_at',
            ]);
        });
    }
};
