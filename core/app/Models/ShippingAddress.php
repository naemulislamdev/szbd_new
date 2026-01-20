<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShippingAddress extends Model
{
    protected $casts = [
        'customer_id' => 'integer',
        'is_billing' => 'integer',
    ];
}
