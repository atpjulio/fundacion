<?php

namespace App\Models\Shared;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use stdClass;

class City extends Model
{
  protected $table = 'new_cities';
  protected $fillable = [
    'state_id',
    'code',
    'name',
  ];
  private $sortField = 'code';

  /**
   * Relations
   */

  public function state()
  {
    return $this->belongsTo(State::class);
  }

  /**
   * Methods
   */
  protected function createCities()
  {
    // Excel::load('public/files/' . config('constants.citiesFilename'), function ($reader) {
    //   $states = State::all();
    //   foreach ($states as $state) {
    //     foreach ($reader->get() as $line) {
    //       if ($line->codigo_deapartamento == $state->code) {
    //         $this->create([
    //           'state_code' => $state->code,
    //           'code' => $line->codigo_municipio,
    //           'name' => $line->nombre_municipio,
    //         ]);
    //       }
    //     }
    //   }
    // });
  }

  protected function getCitiesByStateId($stateCode)
  {
    return $this->where('state_code', $stateCode)
      ->get()
      ->pluck('name', 'code');
  }

  protected function getCityByCode($code)
  {
    $result = $this->where('code', $code)
      ->first();

    return $result ? $result->name : "";
  }

  protected function getCityByCodeAndState($stateCode, $code)
  {
    $result = $this->where('code', sprintf("%03d", $code))
      ->where('state_code', sprintf("%02d", $stateCode))
      ->first();

    return $result ? ucwords(strtolower($result->name)) : "";
  }

  protected function getForSelect($stateId = null, $defaultText = 'Seleccione')
  {
    $defaultOption = new stdClass();

    $defaultOption->value = 0;
    $defaultOption->name = $defaultText;

    $query = $this->select('id as value', DB::raw("CONCAT(code, ' - ', name) as name"));

    if (!is_null($stateId)) {
      $query->where('state_id', $stateId);
    }

    return $query->orderBy($this->sortField)
      ->get()
      ->prepend($defaultOption);
  }
}
