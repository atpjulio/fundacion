<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

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
            'code' => 'required|min:6|unique:eps',
            'name' => 'required|min:3',
            'nit' => 'required|min:9|unique:eps',
            'daily_price' => 'required',
            'address' => 'required',
            'city' => 'required|min:3',
            'state' => 'required',
            'phone' => 'required|min:7',
        ];
    }
}
