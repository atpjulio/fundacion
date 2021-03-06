<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreInvoiceSerieRequest extends FormRequest
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
      'name'            => ['required', 'max:255'],
      'resolution_date' => ['nullable', 'date_format:Y-m-d'],
      'number_from'     => ['required'],
      'number_to'       => ['required', 'gt:number_from'],
      'number'          => ['required', 'gte:number_from', 'lte:number_to'],
      'resolution'      => ['nullable', 'max:50'],
      'prefix'          => ['nullable', 'max:10'],
    ];
  }
}
