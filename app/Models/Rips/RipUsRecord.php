<?php

namespace App\Models\Rips;

use Illuminate\Database\Eloquent\Model;

class RipUsRecord extends Model
{
  protected $fillable = [
    'rip_id',
    'patient_dni_type',
    'patient_dni',
    'eps_code',
    'patient_type',
    'last_name',
    'first_name',
    'age_type',
    'gender',
    'state',
    'city',
    'patient_zone',
  ];
}
