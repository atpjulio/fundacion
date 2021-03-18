<?php

namespace App\Http\Controllers;

use App\Facades\AjaxResponse;
use App\Models\Patients\Companion;
use Illuminate\Http\Request;

class ParticipantController extends Controller
{
  /**
   * Companions
   */

  public function getCompanions()
  {
    return view('participant.companion.index');
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
