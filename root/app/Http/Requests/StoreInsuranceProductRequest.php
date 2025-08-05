<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Enums\InsuranceType;
use App\Http\Requests\Traits\InsuranceProductValidationRules;

class StoreInsuranceProductRequest extends FormRequest
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
        return $this->baseRules();
    }
}
