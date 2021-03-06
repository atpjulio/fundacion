<?php

namespace App\Http\Controllers;

use App\Facades\AjaxResponse;
use App\Http\Requests\StoreInvoiceSerieRequest;
use App\Http\Requests\UpdateInvoiceSerieRequest;
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

  public function editInvoiceSerie($merchantId, $serieId)
  {
    $merchant = Merchant::find($merchantId);
    if (!$merchant) {
      Session::flash('message_danger', 'Información de empresa no encontrada');
      return redirect()->route('merchant.index');
    }
    $serie = InvoiceSerie::find($serieId);
    if (!$serie) {
      Session::flash('message_danger', 'Información de serie no encontrada');
      return redirect()->route('invoice.serie.index', ['merchantId' => $merchantId]);
    }
    return view('invoice.series.edit', compact('merchant', 'serie'));
  }

  public function updateInvoiceSerie(UpdateInvoiceSerieRequest $request, $merchantId, $serieId)
  {
    InvoiceSerie::updateRecord($request, $merchantId, $serieId);

    Session::flash('message', 'Serie de factura actualizada exitosamente');
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
