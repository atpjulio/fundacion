<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AuthorizationCompanion extends Model
{
  use SoftDeletes;

  protected $fillable = [
    'authorization_id',
    'eps_service_id',
    'dni_type',
    'dni',
    'name',
    'phone',
    'notes'
  ];
  /**
   * The attributes that should be mutated to dates.
   *
   * @var array
   */
  protected $dates = ['deleted_at'];

  public function authorization()
  {
    return $this->belongsTo(Authorization::class);
  }
}
