<?php

namespace App\Models\Patients;

use App\Models\Shared\City;
use App\Models\Shared\State;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class PatientData extends Model
{
  protected $table = 'patient_data';
  protected $fillable = [
    'patient_id',
    'city_id',
    'state_id',
    'gender',
    'type',
    'birth_date',
    'zone'
  ];

  /**
   * Attributes
   */

  public function getAgeAttribute()
  {
    return Carbon::createFromDate(
      substr($this->birth_date, 0, 4),
      substr($this->birth_date, 5, 2),
      substr($this->birth_date, 8, 2)
    )->age;
  }

  public function getDaysAttribute()
  {
    return Carbon::createFromDate(
      substr($this->birth_date, 0, 4),
      substr($this->birth_date, 5, 2),
      substr($this->birth_date, 8, 2)
    )
      ->diff(Carbon::now())
      ->format('%d');
  }

  public function getMonthsAttribute()
  {
    return Carbon::createFromDate(
      substr($this->birth_date, 0, 4),
      substr($this->birth_date, 5, 2),
      substr($this->birth_date, 8, 2)
    )
      ->diff(Carbon::now())
      ->format('%m');
  }

  /**
   * Relationships
   */

  public function patient()
  {
    return $this->belongsTo(Patient::class);
  }

  public function city()
  {
    return $this->belongsTo(City::class);
  }

  public function state()
  {
    return $this->belongsTo(State::class);
  }
}
