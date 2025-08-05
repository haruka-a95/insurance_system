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
            'policy_number'   => 'required|string|max:50',
            'customer_id'     => 'required|integer|exists:customers,id',
            'product_id'      => 'required|integer|exists:insurance_products,id',
            'premium_amount'  => 'required|numeric|min:0',
            'status'          => 'required|string|in:' . implode(',', PolicyStatus::values()),
        ];
    }
}