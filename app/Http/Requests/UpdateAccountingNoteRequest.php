<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAccountingNoteRequest extends FormRequest
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
            // 'invoice_number' => 'required',
            // 'amount' => 'required',
            'created_at' => 'required',
            'notePucs.*' => 'required',
            'notePucs' => 'required',
            'pucDescription.*' => 'required',
        ];
    }
}
