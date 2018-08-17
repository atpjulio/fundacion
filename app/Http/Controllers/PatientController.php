<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePatientRequest;
use App\Http\Requests\UpdatePatientRequest;
use App\Patient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class PatientController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'admin'], ['except' => 'getDayRange']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $patients = Patient::all();

        return view('patient.index', compact('patients'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('patient.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StorePatientRequest $request)
    {
        Patient::storeRecord($request);

        Session::flash('message', 'Usuario guardado exitosamente');
        return redirect()->route('patient.index');
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
        $patient = Patient::find($id);
        $address = $patient->address;
        $phone = $patient->phone;

        return view('patient.edit', compact('patient', 'address', 'phone'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdatePatientRequest $request, $id)
    {
        Patient::updateRecord($request);

        Session::flash('message', 'Usuario actualizado exitosamente');
        return redirect()->route('patient.index');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $patient = Patient::find($id);

        $patient->delete();

        Session::flash('message', 'Usuario borrado exitosamente');
        return redirect()->route('patient.index');
    }

    public function getDayRange($yearMonth)
    {
        $year = explode("-", $yearMonth)[0];
        $month = sprintf("%02d", explode("-", $yearMonth)[1]);

        $finalDay = \Carbon\Carbon::parse($year."-".$month."-01")->endOfMonth()->format("d");

        return view('partials._birth_day', compact('finalDay'));
    }
}
