<?php

namespace App\Services;

use App\Models\Order;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class LoyaltyService
{
    // order_status values that count as "completed"
    public const COMPLETED_STATUSES = ['Delivered', 'Confirmed'];

    public const TIERS = ['New', 'Bronze', 'Silver', 'Gold', 'VIP'];

    public const TIER_COLORS = [
        'VIP'    => '#7b61ff',
        'Gold'   => '#ffc107',
        'Silver' => '#adb5bd',
        'Bronze' => '#fd7e14',
        'New'    => '#4f8cff',
    ];

    /**
     * Determine tier based on completed order count + total completed spend.
     * Rule: New = total orders (any status) <= 1
     * Otherwise highest tier whose order-count OR amount threshold is met.
     */
    public function resolveTier(int $totalOrders, int $completedOrders, float $totalSpend): string
    {
        if ($totalOrders <= 1) {
            return 'New';
        }

        if ($completedOrders >= 10 || $totalSpend >= 10000) {
            return 'VIP';
        }

        if ($completedOrders >= 6 || $totalSpend >= 5000) {
            return 'Gold';
        }

        if ($completedOrders >= 3 || $totalSpend >= 3000) {
            return 'Silver';
        }

        if ($completedOrders >= 1 || $totalSpend >= 1000) {
            return 'Bronze';
        }

        return 'New';
    }

    /**
     * Recalculate and persist tier for a single user.
     */
    public function updateUserTier(User $user): void
    {
        $totalOrders = Order::where('customer_id', $user->id)->count();

        $completedStats = Order::where('customer_id', $user->id)
            ->whereIn('order_status', self::COMPLETED_STATUSES)
            ->selectRaw('COUNT(*) as cnt, COALESCE(SUM(order_amount),0) as total')
            ->first();

        $completedOrders = (int) $completedStats->cnt;
        $totalSpend = (float) $completedStats->total;

        $tier = $this->resolveTier($totalOrders, $completedOrders, $totalSpend);

        $user->update([
            'loyalty_tier'              => $tier,
            'completed_orders_count'    => $completedOrders,
            'total_completed_amount'    => $totalSpend,
            'loyalty_tier_updated_at'   => now(),
        ]);
    }

    /**
     * Recalculate tiers for ALL users (used by scheduled command).
     */
    public function recalculateAllTiers(): void
    {
        // Aggregate all order stats per customer in one query (fast, no N+1)
        $orderStats = Order::select('customer_id')
            ->selectRaw('COUNT(*) as total_orders')
            ->selectRaw('SUM(CASE WHEN order_status IN ("' . implode('","', self::COMPLETED_STATUSES) . '") THEN 1 ELSE 0 END) as completed_orders')
            ->selectRaw('SUM(CASE WHEN order_status IN ("' . implode('","', self::COMPLETED_STATUSES) . '") THEN order_amount ELSE 0 END) as total_spend')
            ->groupBy('customer_id')
            ->get()
            ->keyBy('customer_id');

        User::chunkById(500, function ($users) use ($orderStats) {
            foreach ($users as $user) {
                $stat = $orderStats->get($user->id);

                $totalOrders = $stat->total_orders ?? 0;
                $completedOrders = $stat->completed_orders ?? 0;
                $totalSpend = $stat->total_spend ?? 0;

                $tier = $this->resolveTier((int) $totalOrders, (int) $completedOrders, (float) $totalSpend);

                $user->update([
                    'loyalty_tier'              => $tier,
                    'completed_orders_count'    => $completedOrders,
                    'total_completed_amount'    => $totalSpend,
                    'loyalty_tier_updated_at'   => now(),
                ]);
            }
        });
    }

    /**
     * Summary stats for the top cards.
     */
    public function getSummary(string $startDate, string $endDate): array
    {
        $totalCustomers = User::whereBetween('created_at', [$startDate, $endDate . ' 23:59:59'])->count();
        // If you want "all-time total" instead of date-filtered, replace above with: User::count();

        $loyalCustomers = User::whereIn('loyalty_tier', ['Silver', 'Gold', 'VIP'])->count();
        $vipCustomers = User::where('loyalty_tier', 'VIP')->count();
        $repeatCustomers = User::where('completed_orders_count', '>=', 2)->count();

        $totalAllCustomers = User::count();

        $repeatRate = $totalAllCustomers > 0
            ? round(($repeatCustomers / $totalAllCustomers) * 100, 2)
            : 0;

        return [
            'total_customers'    => $totalAllCustomers,
            'loyal_customers'    => $loyalCustomers,
            'loyal_percentage'   => $totalAllCustomers > 0 ? round(($loyalCustomers / $totalAllCustomers) * 100, 2) : 0,
            'vip_customers'      => $vipCustomers,
            'vip_percentage'     => $totalAllCustomers > 0 ? round(($vipCustomers / $totalAllCustomers) * 100, 2) : 0,
            'repeat_customers'   => $repeatCustomers,
            'repeat_rate'        => $repeatRate,
        ];
    }

    /**
     * Tier distribution for the donut chart.
     */
    public function getTierDistribution(): array
    {
        $total = User::count();

        $counts = User::select('loyalty_tier', DB::raw('COUNT(*) as cnt'))
            ->groupBy('loyalty_tier')
            ->pluck('cnt', 'loyalty_tier');

        $result = [];
        foreach (self::TIERS as $tier) {
            $count = (int) ($counts[$tier] ?? 0);
            $result[strtolower($tier)] = $count;
            $result[strtolower($tier) . '_percentage'] = $total > 0 ? round(($count / $total) * 100, 2) : 0;
        }

        return $result;
    }

    /**
     * Loyalty trend chart data (daily / weekly / monthly) between two dates.
     */
    public function getTrend(string $startDate, string $endDate, string $interval = 'daily'): array
    {
        $start = Carbon::parse($startDate)->startOfDay();
        $end = Carbon::parse($endDate)->endOfDay();

        $format = match ($interval) {
            'weekly'  => '%x-W%v',   // ISO year-week
            'monthly' => '%Y-%m',
            default   => '%Y-%m-%d',
        };

        // New customers: registered in period, grouped by date
        $newCustomers = User::whereBetween('created_at', [$start, $end])
            ->selectRaw("DATE_FORMAT(created_at, '%Y-%m-%d') as d, COUNT(*) as cnt")
            ->groupBy('d')
            ->pluck('cnt', 'd');

        // Repeat customers: distinct repeat-tier customers who placed a completed order in period
        $repeatCustomers = Order::join('users', 'users.id', '=', 'orders.customer_id')
            ->whereIn('orders.order_status', self::COMPLETED_STATUSES)
            ->where('users.completed_orders_count', '>=', 2)
            ->whereBetween('orders.created_at', [$start, $end])
            ->selectRaw("DATE_FORMAT(orders.created_at, '%Y-%m-%d') as d, COUNT(DISTINCT orders.customer_id) as cnt")
            ->groupBy('d')
            ->pluck('cnt', 'd');

        // VIP customers: distinct VIP-tier customers who placed an order in period
        $vipCustomers = Order::join('users', 'users.id', '=', 'orders.customer_id')
            ->where('users.loyalty_tier', 'VIP')
            ->whereBetween('orders.created_at', [$start, $end])
            ->selectRaw("DATE_FORMAT(orders.created_at, '%Y-%m-%d') as d, COUNT(DISTINCT orders.customer_id) as cnt")
            ->groupBy('d')
            ->pluck('cnt', 'd');

        $labels = [];
        $newData = [];
        $repeatData = [];
        $vipData = [];

        $cursor = $start->copy();
        while ($cursor->lte($end)) {
            $key = $cursor->format('Y-m-d');
            $labels[] = $cursor->format('M d');
            $newData[] = (int) ($newCustomers[$key] ?? 0);
            $repeatData[] = (int) ($repeatCustomers[$key] ?? 0);
            $vipData[] = (int) ($vipCustomers[$key] ?? 0);
            $cursor->addDay();
        }

        return [
            'labels'             => $labels,
            'new_customers'      => $newData,
            'repeat_customers'   => $repeatData,
            'vip_customers'      => $vipData,
        ];
    }

    /**
     * Full report payload consumed by the dashboard (cards + donut + trend).
     */
    public function getReport(string $startDate, string $endDate, string $interval = 'daily'): array
    {
        $summary = $this->getSummary($startDate, $endDate);
        $tier = $this->getTierDistribution();
        $trend = $this->getTrend($startDate, $endDate, $interval);

        return array_merge($summary, [
            'tier'  => $tier,
            'trend' => $trend,
        ]);
    }
}
