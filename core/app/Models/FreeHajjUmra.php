<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FreeHajjUmra extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'free_hajj_umra';

    protected $guarded = ['id'];

    protected $casts = [

        'has_valid_passport'            => 'boolean',
        'passport_validity_6_months'    => 'boolean',
        'can_self_finance'              => 'boolean',
        'terms_accepted'                => 'boolean',
        'selection_decision_accepted'   => 'boolean',
        'passport_expiry_date'          => 'date',
        'reviewed_at'                   => 'datetime',
        'age'                           => 'integer',
    ];

    protected $hidden = ['deleted_at'];

    // -------------------------------------------------------
    // Boot: auto-generate application_reference on create
    // -------------------------------------------------------
    protected static function booted(): void
    {
        static::creating(function (self $model) {
            if (empty($model->application_reference)) {
                $model->application_reference = self::generateReference();
            }
        });
    }

    private static function generateReference(): string
    {
        $year    = now()->format('Y');
        $latest  = self::withTrashed()
            ->whereYear('created_at', $year)
            ->lockForUpdate()
            ->count();

        return 'UHJ-' . $year . '-' . str_pad($latest + 1, 6, '0', STR_PAD_LEFT);
    }

    // -------------------------------------------------------
    // Relationships
    // -------------------------------------------------------
    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    // -------------------------------------------------------
    // Scopes
    // -------------------------------------------------------
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopeByType($query, string $type)
    {
        return $query->where('pilgrimage_type', $type);
    }
}
