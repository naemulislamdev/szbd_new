<?php

namespace App\Console\Commands;

use App\Services\LoyaltyService;
use Illuminate\Console\Command;

class UpdateLoyaltyTiers extends Command
{
    protected $signature = 'loyalty:update-tiers';

    protected $description = 'Recalculate loyalty tier, completed order count, and total spend for all customers';

    public function handle(LoyaltyService $loyaltyService): int
    {
        $this->info('Recalculating customer loyalty tiers...');

        $start = microtime(true);
        $loyaltyService->recalculateAllTiers();
        $duration = round(microtime(true) - $start, 2);

        $this->info("Done. Took {$duration}s.");

        return self::SUCCESS;
    }
}
