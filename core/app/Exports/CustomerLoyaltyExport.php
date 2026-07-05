<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class CustomerLoyaltyExport implements FromQuery, WithHeadings, WithMapping
{
    public function __construct(
        protected ?string $loyaltyTier = null,
        protected ?string $orderStartDate = null,
        protected ?string $orderEndDate = null
    ) {}

    public function query()
    {
        $query = User::query()
            ->select([
                'id',
                'name',
                'f_name',
                'l_name',
                'email',
                'phone',
                'is_active',
                'loyalty_tier',
                DB::raw('(SELECT COUNT(*) FROM orders WHERE orders.customer_id = users.id' .
                    ($this->orderStartDate && $this->orderEndDate ? ' AND orders.created_at BETWEEN "' . $this->orderStartDate . ' 00:00:00" AND "' . $this->orderEndDate . ' 23:59:59"' : '') .
                    ') as total_order'),
                DB::raw('(SELECT COUNT(*) FROM orders WHERE orders.customer_id = users.id AND orders.order_status = "canceled"' .
                    ($this->orderStartDate && $this->orderEndDate ? ' AND orders.created_at BETWEEN "' . $this->orderStartDate . ' 00:00:00" AND "' . $this->orderEndDate . ' 23:59:59"' : '') .
                    ') as total_cancelled'),
                DB::raw('(SELECT COUNT(*) FROM orders WHERE orders.customer_id = users.id AND orders.order_status = "confirmed"' .
                    ($this->orderStartDate && $this->orderEndDate ? ' AND orders.created_at BETWEEN "' . $this->orderStartDate . ' 00:00:00" AND "' . $this->orderEndDate . ' 23:59:59"' : '') .
                    ') as total_confirmed'),
                DB::raw('(SELECT COALESCE(SUM(order_amount), 0) FROM orders WHERE orders.customer_id = users.id' .
                    ($this->orderStartDate && $this->orderEndDate ? ' AND orders.created_at BETWEEN "' . $this->orderStartDate . ' 00:00:00" AND "' . $this->orderEndDate . ' 23:59:59"' : '') .
                    ') as total_order_amount'),
                DB::raw('(SELECT COALESCE(SUM(order_amount), 0) FROM orders WHERE orders.customer_id = users.id AND orders.order_status = "confirmed"' .
                    ($this->orderStartDate && $this->orderEndDate ? ' AND orders.created_at BETWEEN "' . $this->orderStartDate . ' 00:00:00" AND "' . $this->orderEndDate . ' 23:59:59"' : '') .
                    ') as total_confirmed_amount'),
            ])
            ->latest('id');

        if ($this->orderStartDate && $this->orderEndDate) {
            $query->whereExists(function ($q) {
                $q->select(DB::raw(1))
                    ->from('orders')
                    ->whereColumn('orders.customer_id', 'users.id')
                    ->whereBetween('orders.created_at', [$this->orderStartDate . ' 00:00:00', $this->orderEndDate . ' 23:59:59']);
            });
        }

        if ($this->loyaltyTier) {
            $query->where('loyalty_tier', $this->loyaltyTier);
        }

        return $query;
    }

    public function headings(): array
    {
        return [
            'SL',
            'Name',
            'Phone',
            'Email',
            'Total Order',
            'Total Order Amount (৳)',    // ✅ নতুন
            'Confirmed',
            'Confirmed Amount (৳)',      // ✅ নতুন
            'Cancelled',
            'Loyalty Tier',
            'Status',
        ];
    }

    public function map($row): array
    {
        static $sl = 0;
        $sl++;

        $name = $row->name ?: trim($row->f_name . ' ' . $row->l_name);

        return [
            $sl,
            $name ?: 'N/A',
            $row->phone,
            $row->email,
            $row->total_order,
            number_format($row->total_order_amount, 2),      // ✅ নতুন
            $row->total_confirmed,
            number_format($row->total_confirmed_amount, 2),  // ✅ নতুন
            $row->total_cancelled,
            $row->loyalty_tier,
            $row->is_active ? 'Active' : 'Blocked',
        ];
    }
}
