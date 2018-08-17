<?php

namespace App\Http\Requests;

use App\Eps;
use Illuminate\Foundation\Http\FormRequest;

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
        return [
            'code' => 'required|unique:eps_services',
            'name' => 'required'
        ];
    }

}
