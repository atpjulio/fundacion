<?php

namespace App\Models\Merchants;

use App\Models\Shared\Address;

class MerchantAddress extends Address
{
  protected $table = 'merchant_addresses';

  /**
   * Relationships
   */

  public function parent()
  {
    return $this->belongsTo(Merchant::class);
  }
}
