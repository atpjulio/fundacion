<?php

namespace App\Models\Authorizations;

use Illuminate\Database\Eloquent\Model;

class AuthorizationItem extends Model
{
  protected $fillable = [
    'authorization_id',
    'quantity',
    'authorizable_type', // EpsService, Companion
    'authorizable_id',
  ];

  /**
   * Relationships
   */

  public function authorization()
  {
    return $this->belongsTo(Authorization::class);
  }

  public function authorizable()
  {
    return $this->morphTo();
  }
}
