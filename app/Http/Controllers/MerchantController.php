<?php

namespace App\Http\Controllers;

use App\Facades\AjaxResponse;
use App\Http\Requests\StoreMerchantRequest;
use App\Models\Merchants\Merchant;
use App\Models\Shared\State;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use stdClass;

class MerchantController extends Controller
{
  public function getMerchants()
  {
    return view('merchant.index');
  }
  
  public function createMerchant()
  {
    $states = State::getForSelect(config('constants.default.country'));
    
    $defaultOption = new stdClass();

    $defaultOption->value = 0;
    $defaultOption->name  = 'Seleccione';

    $cities = collect([$defaultOption]);

    return view('merchant.create', compact('states', 'cities'));
  }

  public function storeMerchant(StoreMerchantRequest $request)
  {
    Merchant::storeRecord($request);

    Session::flash('message', 'Empresa guardada exitosamente');
    return redirect()->route('merchant.index');
  }

  public function editMerchant(Merchant $merchant)
  {
    return view('merchant.edit', compact('merchant'));
  }


  /**
   * Ajax
   */

  public function getAjaxMerchants(Request $request)
  {
    $merchants = Merchant::getLatestRecords($request);

    return AjaxResponse::okPaginated($merchants, $request->get('links'));
  }
}
