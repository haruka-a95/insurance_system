<?php

namespace App\Models;

use App\Enums\PolicyStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InsurancePolicy extends Model
{
    use HasFactory;
    protected $fillable = [
        'policy_number',
        'customer_id',
        'product_id',
        'start_date',
        'end_date',
        'premium_amount',
        'status'
    ];

    public $casts = [
        'status' => PolicyStatus::class,
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function product()
    {
        return $this->belongsTo(InsuranceProduct::class);
    }
}
