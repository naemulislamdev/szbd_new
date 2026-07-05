<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TrendingKeyword extends Model
{
    protected $fillable = ['keyword', 'sort_order', 'is_active'];

    protected $casts = [
        'sort_order' => 'integer',
        'is_active'  => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}
