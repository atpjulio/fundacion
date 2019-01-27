<?php

namespace App\Http\Controllers;

use App\Entity;
use App\Http\Requests\StoreReceiptRequest;
use App\Http\Requests\UpdateReceiptRequest;
use App\Http\Requests\UploadTxtRequest;
use App\Invoice;
use App\Puc;
use App\Receipt;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class ReceiptController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $receipts = Receipt::searchRecords('');

        return view('accounting.receipt.index', compact('receipts'));
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
        $entities = Entity::all();

        return view('accounting.receipt.create', compact('invoices', 'entities', 'pucs'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreReceiptRequest $request)
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

        if (intval($request->get('entity_id')) == 0) {
            $result = Entity::checkIfExists($request->get('doc'));

            if ($result) {
                Session::flash('message_danger', 'Ya ese documento de entidad se encuentra registrado en el sistema');
                return redirect()->back()->withInput();
            }

            $newEntity = Entity::storeRecord($request);
            $request->request->add([
                'entity_id' => $newEntity ? $newEntity->id : 0
            ]); 
        } else {
            Entity::updateRecord($request);
        }
//        $invoice = Invoice::getInvoiceByNumber($request->get('invoice_number'));

        Receipt::storeRecord($request, $pucs, $amount);

        Session::flash('message', 'Recibo creado exitosamente');
        return redirect()->route('receipt.index');
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
        $receipt = Receipt::find($id);
        $entity = Entity::find($receipt->entity_id);
        $entities = Entity::all();

        return view('accounting.receipt.edit', compact('invoices', 'pucs', 'receipt', 'entities', 'entity'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateReceiptRequest $request, $id)
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


        if ($request->get('entity_id') == "0") {
            $result = Entity::checkIfExists($request->get('doc'));

            if ($result) {
                Session::flash('message_danger', 'Ya ese documento de entidad se encuentra registrado en el sistema');
                return redirect()->back()->withInput();
            }

            $newEntity = Entity::storeRecord($request);
            $request->request->add([
                'entity_id' => $newEntity ? $newEntity->id : 0
            ]); 
        } else {
            Entity::updateRecord($request);
        }

        // $invoice = Invoice::find($request->get('invoice_number'));

        Receipt::updateRecord($request, $pucs, $amount, $id);

        $request->session()->flash('message', 'Recibo actualizado exitosamente');
        return redirect()->route('receipt.index');
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
            $receipt = Receipt::find($id);

            // $invoice = Invoice::find($receipt->invoice_id);
            // $invoice->payment -= $receipt->amount;
            // $invoice->save();

            $receipt->delete();

            Session::flash('message', 'Recibo eliminado exitosamente');
            return redirect()->route('receipt.index');
        }
        Session::flash('message_danger', 'No tienes permiso para borrar recibos. Este movimiento ha sido notificado');
        return redirect()->route('receipt.index');
    }

    public function pdf($id) 
    {
        $receipt = Receipt::find($id);
        $html = \View::make('accounting.receipt.pdf', compact('receipt'));
        $mpdf = new \Mpdf\Mpdf([
            'margin_left' => 20,
            'margin_right' => 15,
            'margin_top' => 48,
            'margin_bottom' => 25,
            'margin_header' => 10,
            'margin_footer' => 10
        ]);
        $mpdf->SetProtection(array('print'));
        $mpdf->SetTitle($receipt->entity->name." - Recibo ".sprintf("%05d", $receipt->id));
        $mpdf->SetAuthor(auth()->user()->full_name);
        $mpdf->SetDisplayMode('fullpage');
        $mpdf->WriteHTML($html);
        $mpdf->Output('Recibo No '.sprintf("%05d", $receipt->id).'.pdf', 'I');
    }

    public function generals()
    {
        $receipts = Receipt::all();

        return view('accounting.receipt.index', compact('receipts'));
    }

    public function import()
    {
        return view('accounting.receipt.import');
    }

    public function importProcess(UploadTxtRequest $request)
    {
        $file = $request->file('txt_file');
        $counter = 0;

        $fileResource  = fopen($file, "r");
        if ($fileResource) {
            while (($line = fgets($fileResource)) !== false) {
                if (strpos($line, "Factura") === false) {
                    if (Receipt::storeRecordFromTxt($line)) {
                        $counter++;
                    }
                }
            }
            fclose($fileResource);
        }         

        if ($counter > 0) {
            Session::flash("message", "Se guardaron $counter recibos exitosamente!");
        } else {
            Session::flash("message_warning", "No se guardó ningún recibo. Es posible que ya estén guardados en el sistema");
        }
        return redirect()->route('receipt.import');
    }

}
