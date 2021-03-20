<?php

namespace App\Http\Controllers;

use App\Facades\AjaxResponse;
use App\Http\Requests\StoreNewEpsServiceRequest;
use App\Http\Requests\UpdateNewEpsServiceRequest;
use App\Models\Eps\EpsService;
use App\Models\Eps\Eps;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class EpsServiceController extends Controller
{
  public function getEpsServices($epsId)
  {
    $eps = Eps::find($epsId);
    if (!$eps) {
      Session::flash('message_danger', 'Información de EPS no encontrada');
      return redirect()->route('new.eps.index');
    }

    return view('new-eps.services.index', compact('eps'));
  }

  public function createEpsService($epsId)
  {
    $eps = Eps::find($epsId);
    if (!$eps) {
      Session::flash('message_danger', 'Información de EPS no encontrada');
      return redirect()->route('new.eps.index');
    }
    return view('new-eps.services.create', compact('eps'));
  }

  public function storeEpsService(StoreNewEpsServiceRequest $request, $epsId)
  {
    EpsService::storeRecord($request, $epsId);

    Session::flash('message', 'Servicio guardado exitosamente');
    return redirect()->route('new.eps.service.index', ['epsId' => $epsId]);
  }

  public function editEpsService($epsId, $serviceId)
  {
    $eps = Eps::find($epsId);
    if (!$eps) {
      Session::flash('message_danger', 'Información de EPS no encontrada');
      return redirect()->route('eps.index');
    }
    $service = EpsService::find($serviceId);
    if (!$service) {
      Session::flash('message_danger', 'Información de servicio no encontrada');
      return redirect()->route('new.eps.service.index', ['epsId' => $epsId]);
    }
    return view('new-eps.services.edit', compact('eps', 'service'));
  }

  public function updateEpsService(UpdateNewEpsServiceRequest $request, $epsId, $serviceId)
  {
    EpsService::updateRecord($request, $epsId, $serviceId);

    Session::flash('message', 'Servicio actualizado exitosamente');
    return redirect()->route('new.eps.service.index', ['epsId' => $epsId]);
  }

  /**
   * Ajax methods
   */

  public function getAjaxEpsServices(Request $request, $epsId)
  {
    $services = EpsService::getLatestRecords($request, $epsId);

    return AjaxResponse::okPaginated($services, $request->get('links'));
  }

  public function deleteAjaxEpsService($epsId, $serviceId)
  {
    EpsService::deleteRecord($serviceId);

    return AjaxResponse::okPaginated();
  }
}
