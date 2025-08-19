<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Arr;

class SearchInsurancePolicyRequest extends FormRequest
{
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
        return [
            'id' => ['nullable', 'integer'],
            'policy_number' => ['nullable', 'string', 'max:50'],
            'customer_id' => ['nullable', 'integer'],
            'product_name' => ['nullable', 'string', 'max:100'],
            'start_date_from' => ['nullable', 'date'],
            'start_date_to' => ['nullable', 'date', 'after_or_equal:start_date_from'],
            'end_date_from' => ['nullable', 'date'],
            'end_date_to' => ['nullable', 'date', 'after_or_equal:end_date_from'],
            'amount_min' => ['nullable', 'numeric', 'min:0'],
            'amount_max' => ['nullable', 'numeric', 'min:0'],
            'status' => ['nullable', 'array'],
            'status.*' => ['in:active,inactive,canceled'],
            'sort' => ['nullable', 'in:id,policy_number,customer_id,start_date,end_date,premium_amount,status'],
            'direction' => ['nullable', 'in:asc,desc'],
        ];
    }

    /**
     * 保険料上限・下限チェック
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $min = $this->input('amount_min');
            $max = $this->input('amount_max');

            if (!is_null($min) && !is_null($max && $min > $max)) {
                $validator->errors()->add('amount_min', '保険料下限は保険料上限以上の値で指定してください。');
                $validator->errors()->add('amount_max', '保険料上限は保険料下限以下の値で指定してください。');
            }
        });
    }

    public function messages(): array
    {
        return [
            'start_date_to.after_or_equal' => '開始日の範囲を正しい範囲で指定してください。',
            'end_date_to.after_or_equal' => '満了日の範囲を正しい範囲で指定してください。',
        ];
    }
}
