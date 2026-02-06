<?php

namespace App\Models;

use App\CPU\Helpers;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;

class Product extends Model
{
    protected $guarded = ['id'];

    protected $casts = [
        'colors' => 'array',
        'color_variant' => 'array',
        'choice_options' => 'array',
        'attributes' => 'array',
        'user_id' => 'integer',
        'brand_id' => 'integer',
        'min_qty' => 'integer',
        'published' => 'integer',
        'tax' => 'float',
        'unit_price' => 'float',
        'status' => 'integer',
        'discount' => 'float',
        'current_stock' => 'integer',
        'free_shipping' => 'integer',
        'featured_status' => 'integer',
        'refundable' => 'integer',
        'featured' => 'integer',
        'flash_deal' => 'integer',
        'seller_id' => 'integer',
        'purchase_price' => 'float',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'shipping_cost' => 'float',
        'multiply_qty' => 'integer',
        'temp_shipping_cost' => 'float',
        'is_shipping_cost_updated' => 'integer'
    ];
    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }
    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }

    public function stocks()
    {
        return $this->hasMany(ProductStock::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class, 'product_id');
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function scopeStatus($query)
    {
        return $query->where('featured_status', 1);
    }

    public function shop()
    {
        return $this->belongsTo(Shop::class, 'seller_id');
    }

    public function seller()
    {
        return $this->belongsTo(Seller::class, 'user_id');
    }

    public function rating()
    {
        return $this->hasMany(Review::class)
            ->select(DB::raw('avg(rating) average, product_id'))
            ->groupBy('product_id');
    }

    public function order_details()
    {
        return $this->hasMany(OrderDetail::class, 'product_id');
    }


    public function order_delivered()
    {
        return $this->hasMany(OrderDetail::class, 'product_id')
            ->where('delivery_status', 'delivered');
    }

    public function wish_list()
    {
        return $this->hasMany(Wishlist::class, 'product_id');
    }

    public function getNameAttribute($name)
    {
        if (strpos(url()->current(), '/admin') || strpos(url()->current(), '/seller')) {
            return $name;
        }
        return $this->translations[0]->value ?? $name;
    }

    public function getDetailsAttribute($detail)
    {
        if (strpos(url()->current(), '/admin') || strpos(url()->current(), '/seller')) {
            return $detail;
        }
        return $this->translations[1]->value ?? $detail;
    }
}
