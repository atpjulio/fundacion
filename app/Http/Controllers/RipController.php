<?php

namespace App\Http\Controllers;

use App\Company;
use App\Eps;
use App\Http\Requests\StoreRipRequest;
use App\Http\Requests\UpdateRipRequest;
use App\Invoice;
use App\Rip;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class RipController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $rips = Rip::all();

        return view('accounting.rip.index', compact('rips'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $epss = Eps::all();
        $invoicesAmount = count(Invoice::getInvoicesByEpsId($epss->toArray()[0]['id'], date("Y-m-d"), date("Y-m-d")));
        $epss = $epss->pluck('name', 'id');
        $companies = Company::all()->pluck('name', 'id');

        return view('accounting.rip.create', compact('epss', 'companies', 'invoicesAmount'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreRipRequest $request)
    {
        $rip = Rip::produceRIPS($request);
        if ($rip) {
            $request->session()->flash('message', 'RIPS generado exitosamente con el nombre de archivo '.substr($rip->url, 12));
            return redirect()->route('rip.index');
        }
        $request->session()->flash('message_danger', 'No existen facturas para el rango de fecha seleccionado');
        return redirect()->back();
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
        $companies = Company::all()->pluck('name', 'id');
        $rip = Rip::find($id);
        $invoicesAmount = count(Invoice::getInvoicesByEpsId($rip->eps_id, $rip->initial_date, $rip->final_date));

        return view('accounting.rip.edit', compact('epss', 'companies', 'rip', 'invoicesAmount'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRipRequest $request, $id)
    {
        $rip = Rip::updateRIPS($request, $id);
        if ($rip) {
            $request->session()->flash('message', 'RIPS generado exitosamente con el nombre de archivo '.substr($rip->url, 12));
            return redirect()->route('rip.index');
        }
        $request->session()->flash('message_danger', 'No existen facturas para el rango de fecha seleccionado');
        return redirect()->back();
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
            $rip = rip::find($id);

            $rip->delete();

            Session::flash('message', 'RIPS eliminado exitosamente');
            return redirect()->route('rip.index');
        }
        Session::flash('message_danger', 'No tienes permiso para borrar recibos. Este movimiento ha sido notificado');
        return redirect()->route('rip.index');
    }

    public function download($id)
    {
        $rip = rip::find($id);

        return Storage::download($rip->url);
        // return response()->download($rip->url);
    }
}
