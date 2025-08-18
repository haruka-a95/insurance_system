<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Http\Requests\Traits\InsuranceProductValidationRules;

class UpdateInsuranceProductRequest extends FormRequest
{
    use InsuranceProductValidationRules;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        //sometimes|requiredでPATCHやPUTで部分更新時にリクエストに含まれるものだけ対象にする
        return collect($this->baseRules())
            ->map(fn($rule) => str_replace('required', 'sometimes|required', $rule))
            ->toArray();
    }
}
