<?php

namespace App\Http\Controllers;

use App\Facades\AjaxResponse;
use App\Http\Requests\StoreNewEpsRequest;
use App\Http\Requests\UpdateNewEpsRequest;
use App\Models\Eps\Eps;
use App\Models\Merchants\Merchant;
use App\Models\Shared\State;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use stdClass;

class NewEpsController extends Controller
{
  /**
   * Eps
   */

  public function getEpss()
  {
    return view('new-eps.index');
  }

  public function createEps()
  {
    $merchants = Merchant::getForSelect(null, '', false);
    $states    = State::getForSelect(config('constants.default.country'));
    
    $defaultOption = new stdClass();

    $defaultOption->value = 0;
    $defaultOption->name  = 'Seleccione';

    $cities = collect([$defaultOption]);

    return view('new-eps.create', compact('merchants', 'states', 'cities'));
  }

  public function storeEps(StoreNewEpsRequest $request)
  {
    Eps::storeRecord($request);

    Session::flash('message', 'EPS guardada exitosamente');
    return redirect()->route('new.eps.index');
  }

  public function editEps($epsId)
  {
    $eps = Eps::find($epsId);
    if (!$eps) {
      Session::flash('message_danger', 'Información de EPS no encontrada');
      return redirect()->route('new.eps.index');
    }
    $eps->load('address');

    $address = $eps->address;

    $merchants = Merchant::getForSelect(null, '', false);
    $states    = State::getForSelect(config('constants.default.country'));
    
    $defaultOption = new stdClass();

    $defaultOption->value = 0;
    $defaultOption->name  = 'Seleccione';

    $cities = collect([$defaultOption]);

    return view('new-eps.edit', compact('eps', 'merchants', 'states', 'cities'));
  }

  public function updateEps(UpdateNewEpsRequest $request, $epsId)
  {
    Eps::updateRecord($request, $epsId);

    Session::flash('message', 'EPS actualizada exitosamente');
    return redirect()->route('new.eps.index');
  }

  /**
   * Eps ajax
   */

  public function getAjaxEpss(Request $request)
  {
    $epss      = Eps::getLatestRecords($request);
    $merchants = Merchant::getForSelect('epss');

    return AjaxResponse::okPaginated($epss, $request->get('links'), compact('merchants'));
  }

  public function deleteAjaxEps($epsId)
  {
    Eps::deleteRecord($epsId);

    return AjaxResponse::okPaginated();
  }
}
