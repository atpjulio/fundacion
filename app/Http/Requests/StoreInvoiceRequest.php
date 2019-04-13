<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreInvoiceRequest extends FormRequest
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
            'number' => [
                'required',
                'numeric',
                Rule::unique('invoices', 'number')->ignore($this->request->get('number'))->whereNull('deleted_at') 
            ]
        ];

        $rules['multiple_codes'] = 'required';
        $rules['multiple_days'] = 'required';
        $rules['multiple_totals'] = 'required';

        return $rules;
    }
}
