<?php

namespace App\Observers;

use App\Models\Eps\Eps;
use App\Models\Authorizations\Authorization;

class AuthorizationObserver
{
  public function creating(Authorization $authorization)
  {
    if (!$authorization->merchant_id) {
      $eps = Eps::findOrFail($authorization->eps_id);
      $authorization->merchant_id = $eps->merchant_id;
    }
  }

  /**
   * Handle the Authorization "created" event.
   *
   * @param  \App\Models\Authorizations\Authorization  $authorization
   * @return void
   */
  public function created(Authorization $authorization)
  {
    //
  }

  /**
   * Handle the Authorization "updated" event.
   *
   * @param  \App\Models\Authorizations\Authorization  $authorization
   * @return void
   */
  public function updated(Authorization $authorization)
  {
    //
  }

  /**
   * Handle the Authorization "deleted" event.
   *
   * @param  \App\Models\Authorizations\Authorization  $authorization
   * @return void
   */
  public function deleted(Authorization $authorization)
  {
    //
  }

  /**
   * Handle the Authorization "restored" event.
   *
   * @param  \App\Models\Authorizations\Authorization  $authorization
   * @return void
   */
  public function restored(Authorization $authorization)
  {
    //
  }

  /**
   * Handle the Authorization "force deleted" event.
   *
   * @param  \App\Models\Authorizations\Authorization  $authorization
   * @return void
   */
  public function forceDeleted(Authorization $authorization)
  {
    //
  }
}
