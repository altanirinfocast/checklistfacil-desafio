<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateCakeRequest extends FormRequest
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
            'name' => [
                Rule::unique('cakes')->ignore($this->route('cake')),
                'min:2',
                'max:150'
            ],
            'price' => 'nullable|decimal:1,2',
            'weight' => 'nullable|integer|numeric',
            'quantity' => 'nullable|integer|numeric',
        ];
    }
}
