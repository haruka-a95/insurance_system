<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SearchCustomerRequest extends FormRequest
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
    public function rules(): array
    {
        return [
            'id' => ['nullable', 'integer'],
            'name' => ['nullable', 'string', 'max:100'],
            'email' => ['nullable', 'string', 'max:255'],
            'updated_from' => ['nullable','date'],
            'updated_to' => ['nullable', 'date', 'after_or_equal:updated_from'],
            'phone' => ['nullable', 'string', 'max:20'],
            'cellphone' => ['nullable', 'string', 'max:255'],
            'address' => ['nullable','string', 'max:255'],
            'sort' => ['nullable', 'in:id,name,email,updated_from,updated_to,phone,cellphone,address'],
            'direction' => ['nullable', 'in:asc,desc'],
        ];
    }

    public function messages(): array
    {
        return [
            'updated_to.after_or_equal' => '更新日起点は更新日終点以降の日付を指定してください。',
        ];
    }
}
