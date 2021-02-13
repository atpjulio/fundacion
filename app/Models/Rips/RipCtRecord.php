<?php

namespace App\Models\Rips;

use Illuminate\Database\Eloquent\Model;

class RipCtRecord extends Model
{
  protected $fillable = [
    'rip_id',
    'merchant_dni_with_zeros',
    'date',
    'prefix',
    'filename',
    'count',
  ];
}
