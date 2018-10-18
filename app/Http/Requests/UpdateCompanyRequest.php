<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCompanyRequest extends FormRequest
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
            'doc' => 'required|unique:companies,nit,'.$this->request->get('id'),
            'name' => 'required',
            'billing_resolution' => 'required',
            'billing_date' => 'required',
            'billing_start' => 'required|numeric',
            'billing_end' => 'required|numeric',
            'logo' => 'image',
        ];
    }
}
