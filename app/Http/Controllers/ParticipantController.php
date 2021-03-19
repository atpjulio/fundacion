<?php

namespace App\Http\Controllers;

use App\Facades\AjaxResponse;
use App\Http\Requests\StoreCompanionRequest;
use App\Http\Requests\StoreNewPatientRequest;
use App\Http\Requests\UpdateCompanionRequest;
use App\Http\Requests\UpdateNewPatientRequest;
use App\Models\Eps\Eps;
use App\Models\Patients\Companion;
use App\Models\Patients\Patient;
use App\Models\Shared\State;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use stdClass;

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

  /**
   * Patients
   */

  public function getPatients()
  {
    return view('participant.patient.index');
  }

  public function createPatient()
  {
    $epss   = Eps::getForSelect(null, '', false);
    $states = State::getForSelect(config('constants.default.country'));
    
    $defaultOption = new stdClass();

    $defaultOption->value = 0;
    $defaultOption->name  = 'Seleccione';

    $cities = collect([$defaultOption]);

    return view('participant.patient.create', compact('epss', 'states', 'cities'));
  }

  public function storePatient(StoreNewPatientRequest $request)
  {
    Patient::storeRecord($request);

    Session::flash('message', 'Paciente guardado exitosamente');
    return redirect()->route('new.patient.index');
  }

  public function editPatient($patientId)
  {
    $patient = Patient::find($patientId);
    if (!$patient) {
      Session::flash('message_danger', 'Información de paciente no encontrada');
      return redirect()->route('new.patient.index');
    }

    $epss   = Eps::getForSelect(null, '', false);
    $states = State::getForSelect(config('constants.default.country'));
    
    $defaultOption = new stdClass();

    $defaultOption->value = 0;
    $defaultOption->name  = 'Seleccione';

    $cities = collect([$defaultOption]);

    return view('participant.patient.edit', compact('patient', 'epss', 'states', 'cities'));
  }

  public function updatePatient(UpdateNewPatientRequest $request, $patientId)
  {
    Patient::updateRecord($request, $patientId);

    Session::flash('message', 'Paciente actualizado exitosamente');
    return redirect()->route('new.patient.index');
  }

  /**
   * Patients Ajax
   */

  public function getAjaxPatients(Request $request)
  {
    $patients = Patient::getLatestRecords($request);

    return AjaxResponse::okPaginated($patients, $request->get('links'));
  }

  public function deleteAjaxPatient($patientId)
  {
    Patient::deleteRecord($patientId);

    return AjaxResponse::okPaginated();
  }
}
