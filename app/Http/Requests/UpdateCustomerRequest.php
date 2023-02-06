<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
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
            'name' => 'nullable|min:2|max:150',
            'email' => [
                'required',
                Rule::unique('customers')->ignore($this->route('customer')),
                'min:6',
                'max:150'
            ],
            'cake_id' => 'required|exists:cakes,id',
        ];
    }
}
