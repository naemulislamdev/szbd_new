<?php

namespace App\Models;

use App\Model\Category;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubCategory extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function childes()
    {
        return $this->hasMany(ChildCategory::class)->orderBy('order_number','asc');
    }
}
