<?php

namespace App\Http\Controllers;

use App\Company;
use App\Egress;
use App\Http\Requests\StoreEgressRequest;
use App\Http\Requests\UpdateEgressRequest;
use App\Puc;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class EgressController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $egresses = Egress::all();

        return view('accounting.egress.index', compact('egresses'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $pucs = Puc::orderBy('code')->get();
        $companies = Company::all()->pluck('name', 'id');        

        return view('accounting.egress.create', compact('pucs', 'companies'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreEgressRequest $request)
    {
        $pucs = [];
        $amount = 0.00;
        $amountDebit = 0.00;

        foreach ($request->get('notePucs') as $key => $value) {
            array_push($pucs, [
                'code' => $value,    
                'description' => $request->get('pucDescription')[$key],
                'type' => $request->get('pucCredit')[$key] > 0 ? 1 : 0,
                'amount' => $request->get('pucCredit')[$key] > 0 ? floatval($request->get('pucCredit')[$key]) : floatval($request->get('pucDebit')[$key]) 
            ]);
            if ($request->get('pucCredit')[$key] > 0) {
                $amount += floatval($request->get('pucCredit')[$key]);
            } else {
                $amountDebit += floatval($request->get('pucDebit')[$key]);
            }

            Puc::updatePuc($value, $request->get('pucDescription')[$key]);
        }

        if ($amount != $amountDebit) {
            Session::flash('message_danger', 'Débitos: '.number_format($amountDebit, 2, ",", ".")
                .' | Cŕeditos: '.number_format($amount, 2, ",", ".")
                .'<br>No coinciden los montos de débito y cŕedito');
            return redirect()->back()->withInput();
        }

        Egress::storeRecord($pucs, $request, $amount);

        $request->session()->flash('message', 'Comprobante de egreso guardado exitosamente');
        return redirect()->route('egress.index');
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
        $pucs = Puc::orderBy('code')->get();
        $egress = Egress::find($id);
        $companies = Company::all()->pluck('name', 'id');

        return view('accounting.egress.edit', compact('pucs', 'egress', 'companies'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateEgressRequest $request, $id)
    {
        $pucs = [];
        $amount = 0.00;
        $amountDebit = 0.00;

        foreach ($request->get('notePucs') as $key => $value) {
            array_push($pucs, [
                'code' => $value,    
                'description' => $request->get('pucDescription')[$key],
                'type' => $request->get('pucCredit')[$key] > 0 ? 1 : 0,
                'amount' => $request->get('pucCredit')[$key] > 0 ? floatval($request->get('pucCredit')[$key]) : floatval($request->get('pucDebit')[$key]) 
            ]);
            if ($request->get('pucCredit')[$key] > 0) {
                $amount += floatval($request->get('pucCredit')[$key]);
            } else {
                $amountDebit += floatval($request->get('pucDebit')[$key]);
            }
            Puc::updatePuc($value, $request->get('pucDescription')[$key]);        
        }

        if ($amount != $amountDebit) {
            Session::flash('message_danger', 'Débitos: '.number_format($amountDebit, 2, ",", ".")
                .' | Cŕeditos: '.number_format($amount, 2, ",", ".")
                .'<br>No coinciden los montos de débito y cŕedito');
            return redirect()->back()->withInput();
        }

        Egress::updateRecord($pucs, $request, $amount, $id);

        $request->session()->flash('message', 'Comprobante de egreso actualizado exitosamente');
        return redirect()->route('egress.index');
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
            $egress = Egress::find($id);

            $egress->delete();

            Session::flash('message', 'Comprobante de egreso eliminado exitosamente');
            return redirect()->route('egress.index');
        }
        Session::flash('message_danger', 'No tienes permiso para borrar comprobantes de egreso. Este movimiento ha sido notificado');
        return redirect()->route('egress.index');
    }

    public function delete($id)
    {
        $egress = Egress::findOrFail($id);

        return view('accounting.egress.delete_modal', compact('egress'));
    }

    public function pdf($id) 
    {
        $egress = Egress::find($id);
        $html = \View::make('accounting.egress.pdf', compact('egress'));
        $mpdf = new \Mpdf\Mpdf([
            'margin_left' => 20,
            'margin_right' => 15,
            'margin_top' => 48,
            'margin_bottom' => 25,
            'margin_header' => 10,
            'margin_footer' => 10
        ]);
        $mpdf->SetProtection(array('print'));
        $mpdf->SetTitle($egress->company->name." - Comprobante de Egreso ".$egress->number);
        $mpdf->SetAuthor($egress->company->name);
        $mpdf->SetDisplayMode('fullpage');
        $mpdf->WriteHTML($html);
        $mpdf->Output('Recibo No '.$egress->number.'.pdf', 'I');
    }
}
