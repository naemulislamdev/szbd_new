<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class Admin extends Authenticatable
{
    use Notifiable, HasRoles;
    protected $guarded = ['id'];

    protected $guard_name = 'admin';

    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }
}
