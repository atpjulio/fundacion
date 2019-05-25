<?php

namespace App\Http\Controllers;

use App\Company;
use App\Egress;
use App\Entity;
use App\Http\Requests\StoreEgressRequest;
use App\Http\Requests\UpdateEgressRequest;
use App\Puc;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\AccountingNote;

class EgressController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $egresses = Egress::searchRecords();

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
        $entities = Entity::orderBy('name', 'asc')->get();

        return view('accounting.egress.create', compact('pucs', 'companies', 'entities'));
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
                .' | Créditos: '.number_format($amount, 2, ",", ".")
                .'<br>No coinciden los montos de débito y crédito');
            return redirect()->back()->withInput();
        }

        if (!$request->get('entity_id')) {
            $entity = Entity::storeRecord($request);

            $request->request->add([
                'entity_id' => $entity->id
            ]);
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
        // $entities = Entity::all();
        $entities = Entity::orderBy('name', 'asc')->get();
        $pucs = Puc::orderBy('code')->get();
        $egress = Egress::find($id);
        $companies = Company::all()->pluck('name', 'id');
        $entity = Entity::find($egress->entity_id);

        $codes = $descriptions = $debits = $credits = [];

        foreach($egress->pucs as $puc) {
            array_push($codes, $puc->code);
            array_push($descriptions, $puc->description);
            array_push($debits, !$puc->type ? $puc->amount : 0);
            array_push($credits, $puc->type ? $puc->amount : 0);
        }

        return view('accounting.egress.edit', compact(
            'pucs', 'egress', 'companies', 'entities', 'entity', 'codes', 'descriptions', 'debits', 'credits'
        ));
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
                .' | Créditos: '.number_format($amount, 2, ",", ".")
                .'<br>No coinciden los montos de débito y crédito');
            return redirect()->back()->withInput();
        }

        if (!$request->get('entity_id')) {
            $entity = Entity::storeRecord($request);

            $request->request->add([
                'entity_id' => $entity->id
            ]);
        } else {
            Entity::updateRecord($request);
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
        $mpdf->Output('Comprobante de Egreso No '.$egress->number.'.pdf', 'I');
    }

    public function volume(Request $request)
    {
        $egressesAmount = count(Egress::getEgressesByDate(date("Y-m")));
        return view('accounting.egress.volume', compact('egressesAmount'));
    }

    public function volumePDF(Request $request)
    {
        $month = sprintf("%02d", $request->get('month'));
        $year = $request->get('year');
        $egresses = Egress::getEgressesByDate($year.'-'.$month);

        if (count($egresses) == 0) {
            Session::flash('message_danger', 'No hay comprobantes de egreso disponibles para el mes y año seleccionado');
            return redirect()->back();
        }

        ini_set("pcre.backtrack_limit", "5000000");

        $html = \View::make('accounting.egress.volume_pdf', compact('egresses'));
        $mpdf = new \Mpdf\Mpdf([
            'margin_left' => 20,
            'margin_right' => 15,
            'margin_top' => 48,
            'margin_bottom' => 25,
            'margin_header' => 10,
            'margin_footer' => 10
        ]);
        $mpdf->SetProtection(array('print'));
        $mpdf->SetTitle("Comprobantes de Egreso del mes de ".config('constants.months.'.$month));
        $mpdf->SetAuthor("Fundación");
        $mpdf->SetDisplayMode('fullpage');
        $mpdf->WriteHTML($html);
        $mpdf->Output("Comprobantes de Egreso del mes de ".config('constants.months.'.$month).'.pdf', 'I');
    }

    public function balance(Request $request)
    {
        $egressesAmount = count(Egress::getEgressesByDate(date("Y-m")));
        return view('accounting.egress.balance', compact('egressesAmount'));
    }

    public function balancePDF(Request $request)
    {
        $month = sprintf("%02d", $request->get('month'));
        $year = $request->get('year');
        $createdAt = $request->get('created_at');
        $egresses = Egress::getEgressesByDate($year.'-'.$month);

        if (count($egresses) == 0) {
            Session::flash('message_danger', 'No hay comprobantes de egreso disponibles para el mes y año seleccionado');
            return redirect()->back();
        }

        $type = 0;
        $initialBalance = 0;
        $initialEgress = $egresses->first();
        $balance = $initialEgress->balance;

        if ($balance and $balance->type == $type) {
            $initialBalance = $balance->amount;
        } else {
            $accountingNote = AccountingNote::whereHas('balance', function ($query) use ($month, $year, $type) {
                $query->where('month', $month)
                    ->where('year', $year)
                    ->where('type', $type);
            })->first();
            $initialBalance = $accountingNote ? $accountingNote->amount : 0;
        }

        $pucs = [];
        $credits = [];
        $debits = [];
        $descriptions = [];
        $ascendingPucs = [];
        $ascendingDescriptions = [];

        foreach ($egresses as $egress) {
            foreach ($egress->pucs as $index => $puc) {
                if (in_array($puc->code, $pucs)) {
                    if ($puc->type) {
                        $credits[$puc->code] += $puc->amount;
                    } else {
                        $debits[$puc->code] += $puc->amount;
                    }
                } else {
                    array_push($pucs, $puc->code);
                    $descriptions[$puc->code] = $puc->description;
                    if ($puc->type) {
                        $credits[$puc->code] = $puc->amount;
                        $debits[$puc->code] = 0;
                    } else {
                        $credits[$puc->code] = 0;
                        $debits[$puc->code] = $puc->amount;
                    }
                }
            }
        }

        sort($pucs, SORT_STRING);
        ksort($credits, SORT_STRING);
        ksort($debits, SORT_STRING);
        ksort($descriptions, SORT_STRING);

        $type = 1;
        $nextMonth = ($month + 1 > 12) ? 1 : $month + 1;
        $nextYear = ($month + 1 > 12) ? $year + 1 : $year;

        $initialEgress->saveBalance($initialBalance - array_sum($debits), $nextMonth, $nextYear, $type);

        foreach ($pucs as $puc) {
            $counter = 1;
            $levelUp = substr($puc, 0, strlen($puc) - $counter);
            $ascendingPucs[$puc] = [];
            $ascendingDescriptions[$puc] = [];

            while (strlen($levelUp) > 0) {
                $exists = Puc::getPuc($levelUp);
                if ($exists and !in_array($levelUp, $ascendingPucs[$puc])) {
                    $ascendingDescriptions[$puc][] = $exists->description;
                    $ascendingPucs[$puc][] = $levelUp;
                }
                $levelUp = substr($puc, 0, strlen($puc) - $counter);
                $counter++;
            }
            $ascendingDescriptions[$puc] = array_reverse($ascendingDescriptions[$puc]);
            $ascendingPucs[$puc] = array_reverse($ascendingPucs[$puc]);
        }

        // dd($pucs, $ascendingPucs);

        // return view('accounting.egress.balance_html', compact(
        //    'pucs','ascendingPucs', 'ascendingDescriptions', 'credits', 
        //    'debits', 'descriptions', 'initialBalance'
        // ));

        ini_set("pcre.backtrack_limit", "5000000");

        $html = \View::make('accounting.egress.balance_pdf', compact(
            'initialEgress', 'egresses', 'month', 'year', 'createdAt',
            'pucs','ascendingPucs', 'ascendingDescriptions', 'credits', 
            'debits', 'descriptions', 'initialBalance' 
        ));
        $mpdf = new \Mpdf\Mpdf([
            'margin_left' => 20,
            'margin_right' => 15,
            'margin_top' => 48,
            'margin_bottom' => 25,
            'margin_header' => 10,
            'margin_footer' => 10
        ]);
        $mpdf->SetProtection(array('print'));
        $mpdf->SetTitle("Balance de Egresos del mes de ".config('constants.months.'.$month));
        $mpdf->SetAuthor("Fundación");
        $mpdf->SetDisplayMode('fullpage');
        $mpdf->WriteHTML($html);
        $mpdf->Output("Balance de Egresos del mes de ".config('constants.months.'.$month).'.pdf', 'I');        
    }

}
