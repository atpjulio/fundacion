<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePatientRequest extends FormRequest
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
            'dni_type' => 'required',
            'dni' => 'required|unique:patients',
            'first_name' => 'required|min:3',
            'last_name' => 'required|min:3',
            'birth_day' => 'required',
            'birth_month' => 'required',
            'birth_year' => 'required',
            'address' => 'required|min:3',
            'city' => 'required|min:3',
            'state' => 'required',
            'phone' => 'required|min:7',
        ];
    }
}
