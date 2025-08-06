<?php
namespace App\Http\Requests\Traits;

use App\Enums\PolicyStatus;

trait InsurancePolicyValidationRules
{
    /**
     * 共通のバリデーションルールを取得
     */

    protected function baseRules():array
    {
        return[
           'policy_number' => 'required|string|max:50',
            'customer_id' => 'required|exists:customers,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'premium_amount' => 'required|numeric',
            'product_ids' => 'required|array',
            'product_ids.*' => 'exists:insurance_products,id',
            'status'          => 'required|string|in:' . implode(',', PolicyStatus::values()),
        ];
    }
}