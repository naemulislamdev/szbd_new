<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\BlogCategory;

class Blog extends Model
{
    use HasFactory;
    protected $guarded = ['id'];


    public function blogCategory()
    {
        return $this->belongsTo(BlogCategory::class, 'category_id', 'id');
    }

}
