<?php
namespace App\Http\Requests\Traits;

use App\Enums\InsuranceType;

trait InsuranceProductValidationRules
{
    /**
     * 共通のバリデーションルールを取得
     */

    protected function baseRules():array
    {
        return[
            'name' =>'required|max:255',
            'type' =>'requited|in:' . implode(',', InsuranceType::values()),
        ];
    }
}