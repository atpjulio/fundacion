<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ConfirmAuthorizationRequest extends FormRequest
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
            'code' => 'required|unique:authorizations',
            'eps_id' => 'required',
            'eps_service_id' => 'required|numeric|min:1',
            'patient_id' => 'required',
            'date_from' => 'required',
            'date_to' => 'required',
        ];
    }
}
