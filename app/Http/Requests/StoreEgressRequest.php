<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreEgressRequest extends FormRequest
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
            'created_at' => 'required',
            'entity_id' => 'required',
            'concept' => 'required',
            'name' => 'required',
            'doc' => 'required',
            'address' => 'required',
            'phone' => 'required',
            'notePucs.*' => 'required',
            'notePucs' => 'required',
            'pucDescription.*' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'concept.required' => 'El concepto es obligatorio',
            'doc.required' => 'El NIT/CC es obligatorio',
        ];
    }
}
