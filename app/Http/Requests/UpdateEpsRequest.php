<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateEpsRequest extends FormRequest
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
            'code' => 'required|min:6|unique:eps,code,'.$this->request->get('id'),
            'name' => 'required|min:3',
            'nit' => 'required|min:9|unique:eps,nit,'.$this->request->get('id'),
            // 'daily_price' => 'required',
            'address' => 'required',
            'city' => 'required|min:3',
            'state' => 'required',
            'phone' => 'required|min:7',
            'names.*' => 'required',
            'prices.*' => 'required',
        ];
    }

    public function messages()
    {
      return [
        'names.*' => 'El campo nombre del precio es obligatorio',
        'prices.*' => 'El precio es obligatorio',
      ];
    }}
