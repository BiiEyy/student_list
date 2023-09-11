<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MyValidationRequest extends FormRequest
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
     * @return array
     */
    public function rules()
    {
        return [
            'id_number' => 'required|integer|max:99999',
            'name' => 'required|string',
            'age' => 'required|numeric|max:100',
            'city' => 'required|string',
            'mobile_number' => 'required|regex:/^09\d{9}$/|max:11',
            'grades' => 'required|numeric|max:100',
            'email' => 'required|email',
        ];
    }

    public function messages()
    {
        return [
            'id_number.max' => 'The id number must be a maximum of 5 digits.',
            'id_number.integer' => 'Invalid Id number, no decimal allowed',
            'age.max' => 'You are too old',
            'mobile_number.regex' => 'The mobile number must start with "09" and must be 11 digits in total',
        ];
    }
}
