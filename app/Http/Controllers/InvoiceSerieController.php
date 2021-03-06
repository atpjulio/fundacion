<?php

namespace App\Http\Controllers;

use App\Facades\AjaxResponse;
use App\Http\Requests\StoreInvoiceSerieRequest;
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

  public function createInvoiceSerie($merchantId)
  {
    $merchant = Merchant::find($merchantId);
    if (!$merchant) {
      Session::flash('message_danger', 'Información de empresa no encontrada');
      return redirect()->route('merchant.index');
    }
    return view('invoice.series.create', compact('merchant'));
  }

  public function storeInvoiceSerie(StoreInvoiceSerieRequest $request, $merchantId)
  {
    InvoiceSerie::storeRecord($request, $merchantId);

    Session::flash('message', 'Serie de factura guardada exitosamente');
    return redirect()->route('invoice.serie.index', ['merchantId' => $merchantId]);
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
