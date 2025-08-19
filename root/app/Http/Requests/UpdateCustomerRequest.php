<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCustomerRequest extends FormRequest
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
            'name'      => 'required|string|max:255',
            'birthday'  => 'nullable|date',
            'phone'     => 'nullable|numeric',
            'cellphone' => 'nullable|numeric',
            'email'     => 'nullable|email|unique:customers,email,' . $this->route('customer'),
            'address'   => 'nullable|string|max:255',
        ];
    }
}
