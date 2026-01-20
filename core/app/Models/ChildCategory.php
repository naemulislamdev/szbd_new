<?php

namespace App\Models;

use App\Model\Category;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChildCategory extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    public function subCategory()
    {
        return $this->belongsTo(SubCategory::class);
    }
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
