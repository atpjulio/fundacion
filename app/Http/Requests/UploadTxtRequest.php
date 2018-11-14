<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Request;

class UploadTxtRequest extends FormRequest
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
            'txt_file' => 'required|checkTxtExtension:'.Request::file('txt_file'),
        ];
    }

    public function messages()
    {
        return [
            'txt_file.required' => 'El archivo .txt es obligatorio',
            'check_txt_extension' => 'La extensión del archivo debe ser .txt',
        ];
    }
}
