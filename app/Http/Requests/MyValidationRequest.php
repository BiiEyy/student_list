<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

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
            'id_number' => 'required|integer|max:99999|no_hidden_spaces',
            'name' => 'required|string|no_hidden_spaces',
            'age' => 'required|numeric|max:100|no_hidden_spaces',
            'city' => 'required|string|no_hidden_spaces',
            'mobile_number' => 'required|regex:/^09\d{9}$/|max:11|no_hidden_spaces',
            'grades' => 'required|numeric|max:100|no_hidden_spaces',
            'email' => 'required|email|no_hidden_spaces',
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
