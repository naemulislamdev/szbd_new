<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShippingConfig extends Model
{
    protected $fillable = [
        'shipping_type',
        'free_shipping_type',
        'free_shipping_min_amount',
        'is_active'
    ];

    public static function getConfig()
    {
        return static::firstOrCreate([], [
            'shipping_type'      => 'order_wise',
            'free_shipping_type' => null,
            'free_shipping_min_amount' => null,
        ]);
    }
}
