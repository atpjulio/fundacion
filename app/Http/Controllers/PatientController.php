<?php

namespace App\Http\Controllers;

use App\Eps;
use App\Http\Requests\StorePatientRequest;
use App\Http\Requests\UpdatePatientRequest;
use App\Http\Requests\UploadExcelRequest;
use App\Http\Requests\UploadTxtRequest;
use App\Patient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class PatientController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'both'], ['except' => 'getDayRange']);
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
        $epss = Eps::all()->pluck('name', 'id');

        if (session()->has('authorization-create')) {
            session()->forget('authorization-create');
        }
        return view('patient.create', compact('epss'));
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

        if (session()->has('authorization-create')) {
            session()->forget('authorization-create');

            return redirect()->route('authorization.create');
        }

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
        $epss = Eps::all()->pluck('name', 'id');
        $patient = Patient::find($id);
        return view('patient.edit', compact('patient', 'epss'));

//        $address = $patient->address;
//        $phone = $patient->phone;
//
//        return view('patient.edit', compact('patient', 'address', 'phone'));
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
        if (auth()->user()->hasRole('admin')) {
            $patient = Patient::find($id);

            $patient->delete();

            Session::flash('message', 'Usuario borrado exitosamente');
            return redirect()->route('patient.index');
        }
        Session::flash('message_danger', 'No tienes permiso para borrar usuarios. Este movimiento ha sido notificado');
        return redirect()->route('authorization.index');
    }

    public function createAuthorization()
    {
        session([ 'authorization-create' => '1']);

        $epss = Eps::all()->pluck('name', 'id');

        return view('patient.create', compact('epss'));
    }

    public function import()
    {
        $epss = Eps::all()->pluck('name', 'code');

        return view('patient.import', compact('epss'));
    }

    public function importProcess(UploadExcelRequest $request)
    {
        $file = $request->file('excel_file');
        $fileName = time().'_'.$file->getClientOriginalName();

        $file->move(config('constants.importFiles'), $fileName);

        Excel::load(config('constants.importFiles').$fileName, function($reader) use ($request) {
            $counter = 0;
            //$data = $reader->get() instanceof RowCollection ? $reader->get() : $reader->get()->first();
            $data = $reader->get();
            foreach ($data as $line) {
                $result = Patient::storeRecordFromExcel($line);
                if ($result) {
                    $counter++;
                }
            }
            if ($counter > 0) {
                Session::flash("message", "Se guardaron $counter usuarios exitosamente!");
            } else {
                Session::flash("message_warning", "No se guardó ningún usuario. Es posible que ya estén guardados en el sistema");
            }
        });
        Storage::delete($fileName);

        return redirect()->route('patient.import');
    }

    public function importProcessTxt(UploadTxtRequest $request)
    {
        $file = $request->file('txt_file');
        $counter = 0;

        $fileResource  = fopen($file, "r");
        if ($fileResource) {
            while (($line = fgets($fileResource)) !== false) {
                if (strpos($line, "SERIAL") === false) {
                    Patient::storeRecordFromTxt($line, $request->get('eps_code'));
                    $counter++;
                }
            }
            fclose($fileResource);
        }         

        if ($counter > 0) {
            Session::flash("message", "Se guardaron $counter usuarios exitosamente!");
        } else {
            Session::flash("message_warning", "No se guardó ningún usuario. Es posible que ya estén guardados en el sistema");
        }
        return redirect()->route('patient.import');
    }
}
