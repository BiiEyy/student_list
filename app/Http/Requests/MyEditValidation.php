<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MyEditValidation extends FormRequest
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
            'editid_number' => 'required|integer|max:99999',
            'editname' => 'required|string',
            'editage' => 'required|numeric|max:100',
            'editcity' => 'required|string',
            'editmobile_number' => 'required|regex:/^09\d{9}$/|max:11',
            'editgrades' => 'required|numeric|max:100',
            'editemail' => 'required|email',
        ];
    }


    public function messages()
    {
        return [
            'editid_number.max' => 'The id number must be a maximum of 5 digits.',
            'editid_number.integer' => 'Invalid Id number, no decimal allowed',
            'editage.max' => 'You are too old',
            'editmobile_number.regex' => 'The mobile number must start with "09" and must be 11 digits in total',
        ];
    }
}
