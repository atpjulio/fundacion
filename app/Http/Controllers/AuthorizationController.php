<?php

namespace App\Http\Controllers;

use App\Authorization;
use App\City;
use App\Eps;
use App\EpsService;
use App\Http\Requests\ConfirmAuthorizationRequest;
use App\Http\Requests\UpdateAuthorizationRequest;
use App\Patient;
use App\State;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Maatwebsite\Excel\Facades\Excel;

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

        $total = Authorization::fullCount();
        $authorizations = Authorization::full();

        return view('authorization.index', compact('total', 'authorizations'));
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
        $patients = Patient::searchRecords('');

        return view('authorization.create', compact('epss', 'services', 'patients', 'initialEpsId'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function store(ConfirmAuthorizationRequest $request)
    {
        Authorization::storeRecord($request);

        Session::flash('message', 'Autorización guardada exitosamente');
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
        $authorization = Authorization::find($id);
        $services = EpsService::getServices($authorization->eps_id)->pluck('name', 'id');
        $epss = $epss->pluck('name', 'id');
        $patients = Patient::searchRecords($authorization->patient->dni);
        $code = $authorization->codec;
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
            $code = $authorization->code;

            $authorization->delete();
            Session::flash('message', 'Autorización eliminada exitosamente');

            if (strpos($code, config('constants.unathorized.prefix')) !== false) {
                return redirect()->route('authorization.index');
            }
            return redirect()->route('authorization.incomplete');
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

    public function excel($id) 
    {
        $authorization = Authorization::find($id);
        if (!$authorization) {
            Session::flash('message_danger', 'No se pudo crear planilla. Por favor intenta nuevamente');
            return redirect()->route('authorization.index');
        }

        Excel::load('public/files/hospedaje.xls', function($excel) use ($authorization) {
            $excel->sheet('FORMATO', function($sheet) use ($authorization) {                    
                $sheet->cell('B10', function($cell) use ($authorization) {
                    $cell->setValue($authorization->patient->full_name);
                });

                if ($authorization->companion) {
                    $sheet->cell('B11', function($cell) use ($authorization) {
                        $cell->setValue($authorization->companion_name);
                    });
                    $sheet->cell('F11', function($cell) use ($authorization) {
                        $cell->setValue('CC - '.$authorization->companion_dni);
                    });
                }

                $sheet->cell('F10', function($cell) use ($authorization) {
                    $cell->setValue($authorization->patient->dni_type.' - '.$authorization->patient->dni);
                });
                $sheet->cell('I10', function($cell) use ($authorization) {
                    $cell->setValue(\Carbon\Carbon::parse($authorization->date_from)->format("d"));
                });
                $sheet->cell('J10', function($cell) use ($authorization) {
                    $cell->setValue(\Carbon\Carbon::parse($authorization->date_from)->format("m"));
                });
                $sheet->cell('K10', function($cell) use ($authorization) {
                    $cell->setValue(\Carbon\Carbon::parse($authorization->date_from)->format("Y"));
                });
                $sheet->cell('I11', function($cell) use ($authorization) {
                    $cell->setValue(\Carbon\Carbon::parse($authorization->date_to)->format("d"));
                });
                $sheet->cell('J11', function($cell) use ($authorization) {
                    $cell->setValue(\Carbon\Carbon::parse($authorization->date_to)->format("m"));
                });
                $sheet->cell('K11', function($cell) use ($authorization) {
                    $cell->setValue(\Carbon\Carbon::parse($authorization->date_to)->format("Y"));
                });
                $sheet->cell('B14', function($cell) use ($authorization) {
                    $cell->setValue(City::getCityByCodeAndState($authorization->patient->state, $authorization->patient->city));
                });
                $sheet->cell('B15', function($cell) use ($authorization) {
                    $cell->setValue(State::getStateByCode($authorization->patient->state));
                });
                $sheet->cell('B16', function($cell) use ($authorization) {
                    $cell->setValue($authorization->diagnosis);
                });
                $sheet->cell('B17', function($cell) use ($authorization) {
                    $cell->setValue($authorization->eps->alias);
                });
                $sheet->cell('J2', function($cell) use ($authorization) {
                    $cell->setValue($authorization->codec ?: 'S/N');
                });
            });
        })->setFilename('Hospedaje_'.$authorization->eps->alias.'_'.$authorization->code)
        ->export('xls');

    }

    public function incomplete()
    {
        if (session()->has('authorization-create')) {
            session()->forget('authorization-create');
        }

        $authorizations = Authorization::incomplete();

        return view('authorization.incomplete', compact('authorizations'));
    }

    public function open()
    {
        $authorizations = Authorization::open();

        return view('authorization.open', compact('authorizations'));
    }

    public function close()
    {
        $total = Authorization::closeCount();
        $authorizations = Authorization::close();

        return view('authorization.close', compact('total', 'authorizations'));
    }

}
