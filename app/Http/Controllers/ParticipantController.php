<?php

namespace App\Http\Controllers;

use App\Facades\AjaxResponse;
use App\Http\Requests\StoreCompanionRequest;
use App\Http\Requests\UpdateCompanionRequest;
use App\Models\Eps\Eps;
use App\Models\Patients\Companion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class ParticipantController extends Controller
{
  /**
   * Companions
   */

  public function getCompanions()
  {
    return view('participant.companion.index');
  }

  public function createCompanion()
  {
    $epss = Eps::getForSelect(null, '', false);

    return view('participant.companion.create', compact('epss'));
  }

  public function storeCompanion(StoreCompanionRequest $request)
  {
    Companion::storeRecord($request);

    Session::flash('message', 'Acompañante guardado exitosamente');
    return redirect()->route('companion.index');
  }

  public function editCompanion($companionId)
  {
    $companion = Companion::find($companionId);
    if (!$companion) {
      Session::flash('message_danger', 'Información de acompañante no encontrada');
      return redirect()->route('companion.index');
    }

    $epss = Eps::getForSelect(null, '', false);

    return view('participant.companion.edit', compact('companion', 'epss'));
  }

  public function updateCompanion(UpdateCompanionRequest $request, $companionId)
  {
    Companion::updateRecord($request, $companionId);

    Session::flash('message', 'Acompañante actualizado exitosamente');
    return redirect()->route('companion.index');
  }

  /**
   * Companions Ajax
   */

  public function getAjaxCompanions(Request $request)
  {
    $companions = Companion::getLatestRecords($request);

    return AjaxResponse::okPaginated($companions, $request->get('links'));
  }

  public function deleteAjaxCompanion($companionId)
  {
    Companion::deleteRecord($companionId);

    return AjaxResponse::okPaginated();
  }
}
