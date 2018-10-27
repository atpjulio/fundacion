<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreReceiptRequest;
use App\Http\Requests\UpdateReceiptRequest;
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
        $receipts = Receipt::all();

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

        return view('accounting.receipt.create', compact('invoices', 'pucs'));
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

        $invoice = Invoice::getInvoiceByNumber($request->get('invoice_number'));

        Receipt::storeRecord($invoice, $pucs, $request->get('notes'), $amount);

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

        return view('accounting.receipt.edit', compact('invoices', 'pucs', 'receipt'));
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

        $invoice = Invoice::find($request->get('invoice_number'));

        Receipt::updateRecord($invoice, $pucs, $request->get('notes'), $amount);

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

            $invoice = Invoice::find($receipt->invoice_id);
            $invoice->payment -= $receipt->amount;
            $invoice->save();

            $receipt->delete();

            Session::flash('message', 'Recibo eliminado exitosamente');
            return redirect()->route('receipt.index');
        }
        Session::flash('message_danger', 'No tienes permiso para borrar recibos. Este movimiento ha sido notificado');
        return redirect()->route('receipt.index');
    }

    public function pdf($id) 
    {
        /*
        $mpdf = new \Mpdf\Mpdf(['tempDir' => storage_path('app')]);

        $mpdf->SetHTMLHeader('<div style="text-align: right; font-weight: bold; font-size: 30px;">INVOICE</div>');
        $mpdf->SetHTMLFooter('<table width="100%"><tr><td width="33%">{DATE Y-m-j}</td><td width="33%" align="center">{PAGENO}/{nbpg}</td><td width="33%" style="text-align: right;">'.env('APP_URL').'</td></tr></table>');

        $view = \View::make('invoice.pdf');
        $mpdf->WriteHTML($view);
        $mpdf->Output('Factura '.date("Y-m-d"), 'I');
        */
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
        $mpdf->SetTitle($receipt->invoice->company->name." - Recibo ".$receipt->number);
        $mpdf->SetAuthor($receipt->invoice->company->name);
        // $mpdf->SetWatermarkText("Recibo");
        // $mpdf->showWatermarkText = true;
        // $mpdf->watermark_font = 'DejaVuSansCondensed';
        // $mpdf->watermarkTextAlpha = 0.1;
        $mpdf->SetDisplayMode('fullpage');
        $mpdf->WriteHTML($html);
        $mpdf->Output('Recibo No '.sprintf("%05d", $receipt->id).'.pdf', 'I');

    }
}
