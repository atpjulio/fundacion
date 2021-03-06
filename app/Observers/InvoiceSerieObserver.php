<?php

namespace App\Observers;

use App\Models\Invoices\InvoiceSerie;

class InvoiceSerieObserver
{
  /**
   * Handle the InvoiceSerie "created" event.
   *
   * @param  \App\Models\Invoices\InvoiceSerie  $invoiceSerie
   * @return void
   */
  public function created(InvoiceSerie $invoiceSerie)
  {
    if ($invoiceSerie->status == config('enum.status.active')) {
      $invoiceSerie->setOtherStatusToInactive();
    }
  }

  /**
   * Handle the InvoiceSerie "updated" event.
   *
   * @param  \App\Models\Invoices\InvoiceSerie  $invoiceSerie
   * @return void
   */
  public function updated(InvoiceSerie $invoiceSerie)
  {
    if ($invoiceSerie->status == config('enum.status.active')) {
      $invoiceSerie->setOtherStatusToInactive();
    }
  }

  /**
   * Handle the InvoiceSerie "deleted" event.
   *
   * @param  \App\Models\Invoices\InvoiceSerie  $invoiceSerie
   * @return void
   */
  public function deleted(InvoiceSerie $invoiceSerie)
  {
    //
  }

  /**
   * Handle the InvoiceSerie "restored" event.
   *
   * @param  \App\Models\Invoices\InvoiceSerie  $invoiceSerie
   * @return void
   */
  public function restored(InvoiceSerie $invoiceSerie)
  {
    //
  }

  /**
   * Handle the InvoiceSerie "force deleted" event.
   *
   * @param  \App\Models\Invoices\InvoiceSerie  $invoiceSerie
   * @return void
   */
  public function forceDeleted(InvoiceSerie $invoiceSerie)
  {
    //
  }
}
