<?php

namespace App\Http\Controllers;

use App\Facades\AjaxResponse;
use App\Models\Invoices\InvoiceSerie;
use App\Models\Merchants\Merchant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class InvoiceSerieController extends Controller
{
  public function getInvoiceSeries($merchantId)
  {
    $merchant = Merchant::find($merchantId);
    if (!$merchant) {
      Session::flash('message_danger', 'Información de empresa no encontrada');
      return redirect()->route('merchant.index');
    }

    return view('invoice.series.index', compact('merchant'));
  }

  public function createInvoiceSerie(Merchant $merchant)
  {
    dd('Vamos bien');
    return view('invoice.series.index');
  }

  /**
   * Ajax methods
   */

  public function getAjaxInvoiceSeries(Request $request, $merchantId)
  {
    $series = InvoiceSerie::getLatestRecords($request, $merchantId);

    return AjaxResponse::okPaginated($series, $request->get('links'));
  }

  public function deleteAjaxInvoiceSerie($merchantId, $serieId)
  {
    InvoiceSerie::deleteRecord($serieId);

    return AjaxResponse::okPaginated();
  }
}
