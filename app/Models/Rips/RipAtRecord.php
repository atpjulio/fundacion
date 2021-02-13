<?php

namespace App\Models\Rips;

use Illuminate\Database\Eloquent\Model;

class RipAtRecord extends Model
{
  protected $fillable = [
    'rip_id',
    'invoice_number',
    'merchant_dni_with_zeros',
    'patient_dni_type',
    'patient_dni',
    'authorization_code',
    'service_code',
    'extra1',
    'service_name',
    'quantity',
    'amount',
  ];
}
