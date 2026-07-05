<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductLandingPage extends Model
{

    protected $guarded = ['id'];
    public function landingPageSection()
    {
        return $this->hasMany(ProductLandingPageSection::class);
    }
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
