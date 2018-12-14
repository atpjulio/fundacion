<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAuthorizationRequest extends FormRequest
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
            'code' => 'unique:authorizations,code,'.$this->request->get('id'),
            'eps_id' => 'required',
            'eps_service_id' => 'required|numeric|min:1',
            'patient_id' => 'required',
            'date_from' => 'required|date_format:Y-m-d',
            'total_days' => 'required',
        ];

        if ($this->request->get('companion')) {
            $rules['companion_dni'] = 'required';
            $rules['companion_name'] = 'required';
        }

        return $rules;
    }

    public function messages()
    {
        return [
            'date_from.date_format' => 'El campo fecha de inicio tiene un valor inválido',
            'companion_dni.required' => 'Número de documento del acompañante no puede estar vacío',
            'companion_name.required' => 'Nombre del acompañante no puede estar vacío',
            'total_days.required' => 'El total de días es obligatorio',
        ];
    }

}
