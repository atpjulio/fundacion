<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePatientRequest extends FormRequest
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
            'eps_id' => 'required',
            'dni_type' => 'required',
            'dni' => 'required|unique:patients,id,'.$this->request->get('id'),
            'first_name' => 'required|min:3',
            'last_name' => 'required|min:3',
            'birth_day' => 'required',
            'birth_month' => 'required',
            'birth_year' => 'required',
            'gender' => 'required',
            'type' => 'required',
//            'address' => 'required|min:3',
//            'city' => 'required|min:3',
//            'state' => 'required',
//            'phone' => 'required|min:7',
        ];
    }
}
