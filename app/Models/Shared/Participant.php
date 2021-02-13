<?php

namespace App\Models\Shared;

use Illuminate\Database\Eloquent\Model;

class Participant extends Model
{
  protected $fillable = [
    'merchant_id',
    'eps_id',
    'dni_type',
    'dni',
    'first_name',
    'last_name',
    'phone',
  ];

  /**
   * Attributes
   */

  public function getFullNameAttribute()
  {
    return $this->first_name . ' ' . $this->last_name;
  }

  public function getBackNameAttribute()
  {
    return $this->last_name . ' ' . $this->first_name;
  }

  /**
   * Methods
   */

  protected function exists($epsId, $dniType, $dni)
  {
    return $this->where('eps_id', $epsId)
      ->where('dni_type', $dniType)
      ->where('dni', $dni)
      ->first();
  }
}
