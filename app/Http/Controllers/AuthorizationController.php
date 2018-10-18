<?php

namespace App\Http\Controllers;

use App\Authorization;
use App\Eps;
use App\EpsService;
use App\Http\Requests\ConfirmAuthorizationRequest;
use App\Http\Requests\UpdateAuthorizationRequest;
use App\Patient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class AuthorizationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (session()->has('authorization-create')) {
            session()->forget('authorization-create');
        }

        $authorizations = Authorization::all();

        return view('authorization.index', compact('authorizations'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (session()->has('authorization-create')) {
            session()->forget('authorization-create');
        }
        $epss = Eps::all();
        $initialEpsId = $epss->toArray()[0]['id'];
        $services = EpsService::getServices($initialEpsId)->pluck('name', 'id');
        $epss = $epss->pluck('name', 'id');
        $patients = Patient::getPatientsForEps($initialEpsId);// Patient::all();

        return view('authorization.create', compact('epss', 'services', 'patients', 'initialEpsId'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        foreach ($request->get('companionDni') as $key => $value) {
            if (strlen($request->get('companionServiceId')[$key]) == 0) {            
                Session::flash('message_danger', 'Valor inválido para el servicio del acompañante');
                return redirect()->back()->withInput();
            }
            if (strlen($value) == 0) {
                Session::flash('message_danger', 'Valor inválido para el documento del acompañante');
                return redirect()->back()->withInput();
            }
        }

        Authorization::storeRecord($request);

        Session::flash('message', 'Autorización '.$request->get('code').' guardada exitosamente');
        return redirect()->route('authorization.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $epss = Eps::all();
        $services = EpsService::getServices($epss->toArray()[$id]['id'])->pluck('name', 'id');
        $epss = $epss->pluck('name', 'id');
        $patients = Patient::all();
        $authorization = Authorization::find($id);
        $code = $authorization->code;
        $dateFrom = $authorization->date_from;
        $dateTo = $authorization->date_to;
        $initialEpsId = $authorization->eps_id;

        return view('authorization.edit', compact('epss', 'services', 'patients', 'authorization', 'code', 'dateFrom', 'dateTo', 'initialEpsId'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateAuthorizationRequest $request, $id)
    {
        Authorization::updateRecord($request);

        Session::flash('message', 'Autorización actualizada exitosamente');
        return redirect()->route('authorization.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (auth()->user()->hasRole('admin')) {
            $authorization = Authorization::find($id);

            $authorization->delete();

            Session::flash('message', 'Autorización eliminada exitosamente');
            return redirect()->route('authorization.index');
        }
        Session::flash('message_danger', 'No tienes permiso para borrar autorizaciones. Este movimiento ha sido notificado');
        return redirect()->route('authorization.index');
    }

    public function confirm(ConfirmAuthorizationRequest $request)
    {
        $eps = Eps::find($request->get('eps_id'));
        $service = EpsService::find($request->get('eps_service_id'));
        $patient = Patient::find($request->get('patient_id'));
        $code = $request->get('code');
        $dateFrom = $request->get('date_from');
        $dateTo = $request->get('date_to');
        $notes = $request->get('notes');
        $show = true;

        return view('authorization.confirmation', compact('eps', 'service', 'patient', 'code', 'dateFrom', 'dateTo', 'notes', 'show'));
    }

    public function createBack(Request $request)
    {
        dd("under construction");
    }
}
