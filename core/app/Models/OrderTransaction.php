<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;

class OrderTransaction extends Model
{
    protected $guarded = ['id'];

    public function seller()
    {
        return $this->belongsTo(Seller::class, 'seller_id');
    }

    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id');
    }
}
