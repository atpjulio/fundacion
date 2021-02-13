<?php

namespace App\Models\Shared;

use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
  protected $fillable = [
    'parent_id',
    'city_id',
    'state_id',
    'line1',
    'line2',
    'country',
  ];

  public function getFullAddressAttribute()
  {
    if (!$this->line2) {
      return $this->line1;
    }
    return $this->line1 . ' ' . $this->line2;
  }
}
