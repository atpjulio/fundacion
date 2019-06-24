<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreEpsRequest extends FormRequest
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
        'code' => [
          'required', 
          'min:6',
          Rule::unique('eps', 'code')->ignore($this->request->get('code'))->whereNull('deleted_at')   
        ],
        'name' => 'required|min:3',
        'nit' => [
          'required',
          'min:9',
          Rule::unique('eps', 'nit')->ignore($this->request->get('nit'))->whereNull('deleted_at')
        ],
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
    }
}
