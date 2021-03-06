<?php

namespace App\Http\Requests;

use App\Models\Shared\City;
use App\Models\Shared\State;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateMerchantRequest extends FormRequest
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
      'dni_type'        => [Rule::in(array_keys(config('constants.companiesDocumentTypes')))],
      'dni'             => ['required', 'max:15'],
      'name'            => ['required', 'max:255'],
      'resolution_date' => ['nullable', 'date_format:Y-m-d'],
      'line1'           => ['required', 'max:255'],
      'city_id'         => [Rule::exists((new City())->getTable(), 'id')],
      'state_id'        => [Rule::exists((new State())->getTable(), 'id')],
    ];
  }
}
