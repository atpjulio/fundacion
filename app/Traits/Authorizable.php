<?php

namespace App\Traits;

use App\Models\Authorizations\AuthorizationItem;

trait Authorizable
{
  public function item()
  {
    return $this->morphMany(AuthorizationItem::class, 'authorizable');
  }
}