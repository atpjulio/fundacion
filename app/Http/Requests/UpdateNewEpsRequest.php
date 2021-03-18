<?php

namespace App\Http\Requests;

use App\Models\Merchants\Merchant;
use App\Models\Shared\City;
use App\Models\Shared\State;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateNewEpsRequest extends FormRequest
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
      'merchant_id'     => [Rule::exists((new Merchant())->getTable(), 'id')],
      'code'            => ['required', 'max:10'],
      'color'           => ['nullable', 'max:7'],
      'nit'             => ['required', 'max:15'],
      'name'            => ['required', 'max:191'],
      'alias'           => ['nullable', 'max:191'],
      'contract_number' => ['nullable', 'max:191'],
      'policy'          => ['nullable', 'max:191'],
      'phone1'          => ['nullable', 'max:191'],
      'phone2'          => ['nullable', 'max:191'],
      'line1'           => ['required', 'max:191'],
      'city_id'         => [Rule::exists((new City())->getTable(), 'id')],
      'state_id'        => [Rule::exists((new State())->getTable(), 'id')],
    ];
  }
}
