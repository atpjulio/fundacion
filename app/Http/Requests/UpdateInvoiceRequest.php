<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateInvoiceRequest extends FormRequest
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
            'company_id' => 'required',
            'number' => 'required|numeric|unique:invoices,id,'.$this->request->get('id'),
        ];

        if ($this->request->get('multiple') == "1") {
            // dd($this->request->all());            
        } else {
            $rules['authorization_code'] = 'required';
            $rules['total'] = 'required|numeric';
        }

        return $rules;
    }
}
