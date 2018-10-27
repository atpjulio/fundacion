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
        $rules = [
            'code' => 'required|unique:authorizations',
            'eps_id' => 'required',
            'eps_service_id' => 'required|numeric|min:1',
            'patient_id' => 'required',
            'date_from' => 'required',
            // 'date_to' => 'required',
        ];

        if ($this->request->get('companion') and $this->request->get('companionDni')) {
            $rules['companionDni.*'] = 'required|companionDniNumber:'.join(",", $this->request->get('companionDni'));
        }

        return $rules;
    }

    public function messages()
    {
        return [
            'patient_id.required' => 'Debes seleccionar un usuario',
            'companion_dni_number' => 'Número de documento del acompañante no se encuentra registrado',
        ];
    }
}
