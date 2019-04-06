<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreRipRequest extends FormRequest
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
            'company_id' => 'required',
            'eps_id' => 'required',
            // 'initial_date' => 'required',
            // 'final_date' => 'required',
            'initial_number' => 'required',
            'final_number' => 'required',
            'created_at' => 'required',
        ];
    }
}
