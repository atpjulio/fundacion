<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Request;

class UploadExcelRequest extends FormRequest
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
            'excel_file' => 'required|checkExcelExtension:'.Request::file('excel_file'),
        ];
    }

    public function messages()
    {
        return [
            'check_excel_extension' => 'La extensión del archivo debe ser .xls o .xlsx',
        ];
    }
}
