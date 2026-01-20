<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LandingPages extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
    public function multiProducts()
    {
        return $this->hasMany(landing_pages_product::class, 'landing_id');
    }
}
