<?php

namespace App\Models;

use App\Enums\ApprovalStatus;
use App\Enums\InsuranceType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InsuranceProduct extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description', 'type', 'approval_status'];

    protected $attributes = [
        'approval_status' => 'pending',
    ];

    protected $casts = [
        'approval_status' => ApprovalStatus::class,
        'type' => InsuranceType::class,
    ];

    public function policies()
    {
        return $this->belongsToMany(InsurancePolicy::class, 'insurance_policy_product');
    }

    public function getStatusLabelAttribute(): string
    {
        return $this->approval_status->label();
    }

    public function getTypeLabelAttribute(): string
    {
        return $this->type->label() ?? '不明';
    }
}
