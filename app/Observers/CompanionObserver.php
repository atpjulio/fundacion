<?php

namespace App\Observers;

use App\Models\Eps\Eps;
use App\Models\Patients\Companion;

class CompanionObserver
{
  public function creating(Companion $companion)
  {
    if (!$companion->merchant_id) {
      $eps = Eps::findOrFail($companion->eps_id);
      $companion->merchant_id = $eps->merchant_id;
    }
  }

  /**
   * Handle the Companion "created" event.
   *
   * @param  \App\Models\Patients\Companion  $companion
   * @return void
   */
  public function created(Companion $companion)
  {
    //
  }

  /**
   * Handle the Companion "updated" event.
   *
   * @param  \App\Models\Patients\Companion  $companion
   * @return void
   */
  public function updated(Companion $companion)
  {
    //
  }

  /**
   * Handle the Companion "deleted" event.
   *
   * @param  \App\Models\Patients\Companion  $companion
   * @return void
   */
  public function deleted(Companion $companion)
  {
    //
  }

  /**
   * Handle the Companion "restored" event.
   *
   * @param  \App\Models\Patients\Companion  $companion
   * @return void
   */
  public function restored(Companion $companion)
  {
    //
  }

  /**
   * Handle the Companion "force deleted" event.
   *
   * @param  \App\Models\Patients\Companion  $companion
   * @return void
   */
  public function forceDeleted(Companion $companion)
  {
    //
  }
}
