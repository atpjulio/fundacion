<?php

namespace App\Http\Requests;

use App\Eps;
use Illuminate\Foundation\Http\FormRequest;
use App\EpsService;
use Illuminate\Validation\Rule;

class StoreEpsServiceRequest extends FormRequest
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
            'eps_id' => 'required',
            'code' => [
                'required',
                // Rule::unique('eps_services', 'code', 'eps_id')->ignore($this->request->get('code'))->whereNull('deleted_at')
            ],
            'name' => 'required',
            'price' => 'numeric',
        ];
        
        return $rules;
    }

}
