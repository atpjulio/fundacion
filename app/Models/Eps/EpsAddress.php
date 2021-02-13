<?php

namespace App\Models\Eps;

use App\Models\Shared\Address;

class EpsAddress extends Address
{
  protected $table = 'eps_addresses';

  /**
   * Relationships
   */

  public function parent()
  {
    return $this->belongsTo(Eps::class);
  }
}
