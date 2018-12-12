<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AuthorizationService extends Model
{
  use SoftDeletes;

  protected $fillable = [
    'authorization_id',
    'eps_service_id',
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
