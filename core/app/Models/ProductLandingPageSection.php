<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductLandingPageSection extends Model
{
    protected $guarded = ['id'];
    public function productLandingpage()
    {
        return $this->belongsTo(ProductLandingPage::class);
    }
}
