<?php

namespace App\Models;

use App\Models\Category;
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

    /**
     * Return an empty childes relationship so that the API always
     * serialises "childes": [] instead of omitting the key entirely.
     * The Flutter app force-unwraps this field with `childes!`.
     */
    public function childes()
    {
        // Self-referencing with a foreign key that will never match,
        // guaranteeing an empty collection every time.
        return $this->hasMany(ChildCategory::class, 'sub_category_id');
    }
}
