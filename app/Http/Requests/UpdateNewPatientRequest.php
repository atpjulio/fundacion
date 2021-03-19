<?php

namespace App\Http\Requests;

use App\Models\Eps\Eps;
use App\Models\Shared\City;
use App\Models\Shared\State;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateNewPatientRequest extends FormRequest
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
      'eps_id'     => [Rule::exists((new Eps())->getTable(), 'id')],
      'dni_type'   => [Rule::in(array_keys(config('constants.documentTypes')))],
      'dni'        => ['required', 'max:15'],
      'first_name' => ['required', 'max:191'],
      'last_name'  => ['required', 'max:191'],
      'phone'      => ['nullable', 'max:20'],
      'birth_date' => ['required', 'date_format:Y-m-d'],
      'city_id'    => [Rule::exists((new City())->getTable(), 'id')],
      'state_id'   => [Rule::exists((new State())->getTable(), 'id')],
    ];
  }
}
