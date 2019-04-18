<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateEpsServiceRequest extends FormRequest
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
        // dd($this->request->all());
        return [
            'code' => 'required',
            // 'code' => 'required|unique:eps_services,code,'.$this->request->get('id'),
            'name' => 'required',
            'price' => 'numeric'
        ];
    }
}
