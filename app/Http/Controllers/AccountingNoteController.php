<?php

namespace App\Http\Controllers;

use App\AccountingNote;
use App\Http\Requests\StoreAccountingNoteRequest;
use App\Http\Requests\UpdateAccountingNoteRequest;
use App\Invoice;
use App\Puc;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class AccountingNoteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $notes = AccountingNote::searchRecords('');

        return view('accounting.note.index', compact('notes'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $invoices = Invoice::all();
        $pucs = Puc::orderBy('code')->get();

        return view('accounting.note.create', compact('invoices', 'pucs'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreAccountingNoteRequest $request)
    {
        $pucs = [];
        $amount = 0.00;
        $amountDebit = 0.00;

        $request->request->add([
            'created_at' => $request->get('created_at').' '.\Carbon\Carbon::now()->format('H:i:s')
        ]);

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

        //$invoice = Invoice::getInvoiceByNumber($request->get('invoice_number'));
        $invoice = new Invoice();
        $invoice->id = 0;
        $invoice->created_at = $request->get('created_at');

        AccountingNote::storeRecord($invoice, $pucs, $request->get('notes'), $amount);

        $request->session()->flash('message', 'Nota de contabilidad guardada exitosamente');
        return redirect()->route('accounting-note.index');
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
        $invoices = Invoice::all();
        $pucs = Puc::orderBy('code')->get();
        $note = AccountingNote::find($id);

        return view('accounting.note.edit', compact('invoices', 'pucs', 'note'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateAccountingNoteRequest $request, $id)
    {
        $pucs = [];
        $amount = 0.00;
        $amountDebit = 0.00;

        $request->request->add([
            'created_at' => $request->get('created_at').' '.\Carbon\Carbon::now()->format('H:i:s')
        ]);

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

        // $invoice = Invoice::getInvoiceByNumber($request->get('invoice_number'));
        $invoice = new Invoice();
        $invoice->id = 0;
        $invoice->created_at = $request->get('created_at');

        AccountingNote::updateRecord($invoice, $pucs, $request->get('notes'), $amount, $id);

        $request->session()->flash('message', 'Nota de contabilidad actualizada exitosamente');
        return redirect()->route('accounting-note.index');
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
            $note = AccountingNote::find($id);

            $note->delete();

            Session::flash('message', 'Nota de contabilidad eliminada exitosamente');
            return redirect()->route('accounting-note.index');
        }
        Session::flash('message_danger', 'No tienes permiso para borrar notas de contabilidad. Este movimiento ha sido notificado');
        return redirect()->route('accounting-note.index');
    }

    public function delete($id)
    {
        $note = AccountingNote::findOrFail($id);

        return view('accounting.note.delete_modal', compact('note'));
    }

}
