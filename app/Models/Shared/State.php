<?php

namespace App\Models\Shared;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use stdClass;

class State extends Model
{
  protected $fillable = [
    'country_iso2',
    'code',
    'name',
  ];
  private $sortField = 'code';

  private $states = [
    '05' => 'ANTIOQUIA',
    '08' => 'ATLÁNTICO',
    '11' => 'BOGOTÁ, D.C.',
    '13' => 'BOLÍVAR',
    '15' => 'BOYACÁ',
    '17' => 'CALDAS',
    '18' => 'CAQUETÁ',
    '19' => 'CAUCA',
    '20' => 'CESAR',
    '23' => 'CÓRDOBA',
    '25' => 'CUNDINAMARCA',
    '27' => 'CHOCÓ',
    '41' => 'HUILA',
    '44' => 'LA GUAJIRA',
    '47' => 'MAGDALENA',
    '50' => 'META',
    '52' => 'NARIÑO',
    '54' => 'NORTE DE SANTANDER',
    '63' => 'QUINDIO',
    '66' => 'RISARALDA',
    '68' => 'SANTANDER',
    '70' => 'SUCRE',
    '73' => 'TOLIMA',
    '76' => 'VALLE DEL CAUCA',
    '81' => 'ARAUCA',
    '85' => 'CASANARE',
    '86' => 'PUTUMAYO',
    '88' => 'ARCHIPIÉLAGO DE SAN ANDRÉS, PROVIDENCIA Y SANTA CATALINA',
    '91' => 'AMAZONAS',
    '94' => 'GUAINÍA',
    '95' => 'GUAVIARE',
    '97' => 'VAUPÉS',
    '99' => 'VICHADA',
  ];

  protected function createStates()
  {
    foreach ($this->states as $code => $name) {
      $this->create([
        'code' => $code,
        'name' => ucwords(mb_strtolower($name, 'UTF-8')),
      ]);
    }
  }

  protected function getStates()
  {
    return $this->states;
  }

  protected function getStateByCode($code)
  {
    $result = $this->where('code', $code)
      ->first();

    return $result ? $result->name : "";
  }

  protected function getForSelect($countryIso2 = null, $defaultText = 'Seleccione')
  {
    $defaultOption = new stdClass();

    $defaultOption->value = 0;
    $defaultOption->name = $defaultText;

    $query = $this->select('id as value', DB::raw("CONCAT(code, ' - ', name) as name"));

    if ($countryIso2) {
      $query->where('country_iso2', $countryIso2);
    }

    $result = $query->orderBy($this->sortField)
      ->get();

    if (!empty($defaultText)) {
      return $result->prepend($defaultOption);
    }
    return $result;
  }
}
