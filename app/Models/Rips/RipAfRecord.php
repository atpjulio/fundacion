<?php

namespace App\Models\Rips;

use Illuminate\Database\Eloquent\Model;

class RipAfRecord extends Model
{
  protected $fillable = [
    'rip_id',
    'merchant_dni_with_zeros',
    'merchant_name',
    'merchant_dni_type',
    'merchant_dni_short',
    'invoice_number',
    'date',
    'initial_date',
    'final_date',
    'eps_code',
    'eps_name',
    'eps_contract_number',
    'extra1',
    'extra2',
    'extra3',
    'extra4',
    'extra5',
    'amount',
  ];
}
