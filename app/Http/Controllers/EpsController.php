<?php

namespace App\Http\Controllers;

use App\Eps;
use App\EpsService;
use App\Http\Requests\StoreEpsRequest;
use App\Http\Requests\StoreEpsServiceRequest;
use App\Http\Requests\UpdateEpsRequest;
use App\Http\Requests\UpdateEpsServiceRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class EpsController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'admin'], ['except' => '']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $epss = Eps::all();
        return view('eps.index', compact('epss'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('eps.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreEpsRequest $request)
    {
        Eps::storeRecord($request);

        Session::flash('message', 'EPS guardada exitosamente');
        return redirect()->route('eps.index');
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
        $eps = Eps::find($id);
        $address = $eps->address;
        $phone = $eps->phone;

        return view('eps.edit', compact('eps', 'address', 'phone'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateEpsRequest $request, $id)
    {
        Eps::updateRecord($request);

        Session::flash('message', 'EPS actualizada exitosamente');
        return redirect()->route('eps.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $eps = Eps::find($id);

        $eps->delete();

        Session::flash('message', 'EPS borrada exitosamente');
        return redirect()->route('eps.index');
    }

    public function services($id)
    {
        $eps = Eps::find($id);
        $services = EpsService::getServices($id);

        return view('eps.services.index', compact('eps','services'));
    }

    public function servicesCreate($id)
    {
        $eps = Eps::find($id);

        return view('eps.services.create', compact('eps'));
    }

    public function servicesStore(StoreEpsServiceRequest $request)
    {
        EpsService::storeRecord($request);

        Session::flash('message', 'Servicio guardado exitosamente');
        return redirect()->route('eps.services.index', ['id' => $request->get('eps_id') ]);
    }

    public function servicesEdit($id)
    {
        $service = EpsService::find($id);
        $eps = Eps::find($service->eps_id);

        return view('eps.services.edit', compact('eps', 'service'));
    }

    public function servicesUpdate(UpdateEpsServiceRequest $request)
    {
        EpsService::updateRecord($request);

        Session::flash('message', 'Servicio actualizado exitosamente');
        return redirect()->route('eps.services.index', ['id' => $request->get('eps_id') ]);
    }

    public function servicesDestroy(Request $request)
    {
        $service = EpsService::find($request->get('id'));

        $service->delete();

        Session::flash('message', 'Servicio borrado exitosamente');
        return redirect()->route('eps.services.index', ['id' => $request->get('eps_id') ]);
    }
}
