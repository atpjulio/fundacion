<?php

namespace App\Observers;

use App\Models\Eps\Eps;
use App\Models\Patients\Patient;

class PatientObserver
{
  public function creating(Patient $patient)
  {
    if (!$patient->merchant_id) {
      $eps = Eps::findOrFail($patient->eps_id);
      $patient->merchant_id = $eps->merchant_id;
    }
  }

  /**
   * Handle the Patient "created" event.
   *
   * @param  \App\Models\Patients\Patient  $patient
   * @return void
   */
  public function created(Patient $patient)
  {
    //
  }

  /**
   * Handle the Patient "updated" event.
   *
   * @param  \App\Models\Patients\Patient  $patient
   * @return void
   */
  public function updated(Patient $patient)
  {
    //
  }

  /**
   * Handle the Patient "deleted" event.
   *
   * @param  \App\Models\Patients\Patient  $patient
   * @return void
   */
  public function deleted(Patient $patient)
  {
    //
  }

  /**
   * Handle the Patient "restored" event.
   *
   * @param  \App\Models\Patients\Patient  $patient
   * @return void
   */
  public function restored(Patient $patient)
  {
    //
  }

  /**
   * Handle the Patient "force deleted" event.
   *
   * @param  \App\Models\Patients\Patient  $patient
   * @return void
   */
  public function forceDeleted(Patient $patient)
  {
    //
  }
}
