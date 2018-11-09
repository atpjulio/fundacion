<?php

namespace App\Http\Controllers;

use App\Authorization;
use App\Company;
use App\Eps;
use App\Http\Requests\StoreInvoiceRequest;
use App\Http\Requests\UpdateInvoiceRequest;
use App\Invoice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class InvoiceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $invoices = Invoice::all();

        return view('invoice.index', compact('invoices'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $companies = Company::all()->pluck('name', 'id');
        $invoices = Invoice::all();
        $lastNumber = count($invoices) > 0 ? $invoices->last()->number : 0;
        $authorizations = Authorization::openForInvoices();

        return view('invoice.create', compact('companies', 'lastNumber', 'authorizations'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreInvoiceRequest $request)
    {
        if ($request->get('multiple') == "1") {
            foreach ($request->get('multiple_codes') as $key => $value) {
                if ($request->get('multiple_days')[$key] == "") {
                    Session::flash('message_danger', 'Falta al menos un campo de días por llenar');
                    return redirect()->back()->withInput();
                }
                if ($request->get('multiple_totals')[$key] == "") {
                    Session::flash('message_danger', 'Falta al menos un campo de total por llenar');
                    return redirect()->back()->withInput();
                }
                if ($value == "") {
                    Session::flash('message_danger', 'Falta al menos un campo de autorización por llenar');
                    return redirect()->back()->withInput();
                }
            }
        }
        $invoice = Invoice::storeRecord($request);

        Session::flash('message', 'Factura '.$invoice->format_number.' guardada exitosamente');
        return redirect()->route('invoice.index');
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
        $companies = Company::all()->pluck('name', 'id');
        $invoice = Invoice::find($id);

        if (!$invoice) {
            Session::flash('message_danger', 'Factura no encontrada');
            return redirect()->back()->withInput();            
        }

        $lastNumber = $invoice->number;
        $authorizations = Authorization::openForInvoices();

        return view('invoice.edit', compact('companies', 'lastNumber', 'authorizations', 'invoice'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateInvoiceRequest $request, $id)
    {
        if ($request->get('multiple') == "1") {
            foreach ($request->get('multiple_codes') as $key => $value) {
                if ($request->get('multiple_days')[$key] == "") {
                    Session::flash('message_danger', 'Falta al menos un campo de días por llenar');
                    return redirect()->back()->withInput();
                }
                if ($request->get('multiple_totals')[$key] == "") {
                    Session::flash('message_danger', 'Falta al menos un campo de total por llenar');
                    return redirect()->back()->withInput();
                }
                if ($value == "") {
                    Session::flash('message_danger', 'Falta al menos un campo de autorización por llenar');
                    return redirect()->back()->withInput();
                }
            }
        }
        Invoice::updateRecord($request);

        Session::flash('message', 'Factura actualizada exitosamente');
        return redirect()->route('invoice.index');
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
            $invoice = Invoice::find($id);

            if (!$invoice) {
                Session::flash('message_danger', 'Factura no encontrada');
                return redirect()->back()->withInput();            
            }

            $note = AccountingNote::where('invoice_id', $invoice->id)->first();
            if ($note) {
                $note->delete();
            }

            $invoice->delete();

            Session::flash('message', 'Factura eliminada exitosamente');
            return redirect()->route('invoice.index');
        }
        Session::flash('message_danger', 'No tienes permiso para borrar facturas. Este movimiento ha sido notificado');
        return redirect()->route('invoice.index');
    }

    public function pdf($id) 
    {
        $invoice = Invoice::find($id);
        if (!$invoice) {
            Session::flash('message_danger', 'Factura no encontrada');
            return redirect()->back()->withInput();            
        }

        $html = \View::make('invoice.pdf', compact('invoice'));
        $mpdf = new \Mpdf\Mpdf([
            'margin_left' => 20,
            'margin_right' => 15,
            'margin_top' => 48,
            'margin_bottom' => 25,
            'margin_header' => 10,
            'margin_footer' => 10
        ]);
        $mpdf->SetProtection(array('print'));
        $mpdf->SetTitle($invoice->company->name." - Factura ".$invoice->format_number);
        $mpdf->SetAuthor($invoice->company->name);
        $mpdf->SetDisplayMode('fullpage');
        $mpdf->WriteHTML($html);
        $mpdf->Output('Factura No '.$invoice->number.'.pdf', 'I');

    }

    public function relation()
    {
        $epss = Eps::all();

        if (!$epss) {
            Session::flash('message_danger', 'No se encontró el listado de EPS');
            return redirect()->back()->withInput();            
        }

        $invoicesAmount = count(Invoice::getInvoicesByEpsId($epss->toArray()[0]['id'], date("Y-m-d"), date("Y-m-d")));
        $epss = $epss->pluck('name', 'id');
        $companies = Company::all()->pluck('name', 'id');

        return view('invoice.relation', compact('epss', 'companies', 'invoicesAmount'));
    }

    public function relationPDF(Request $request) 
    {
        $epsId = $request->get('eps_id');
        $initialDate = $request->get('initial_date');
        $finalDate = $request->get('final_date');
        $companyId = $request->get('company_id');

        $invoices = Invoice::getInvoicesByEpsId($epsId, $initialDate, $finalDate);

        if (count($invoices) == 0) {
            Session::flash('message_danger', 'No hay facturas disponibles para el rango de fecha seleccionado');
            return redirect()->back();
        }

        $company = Company::find($companyId);
        $eps = Eps::find($epsId);

        $html = \View::make('invoice.pdf_relation', compact('invoices', 'company', 'eps', 'initialDate', 'finalDate'));
        $mpdf = new \Mpdf\Mpdf([
            'margin_left' => 20,
            'margin_right' => 15,
            'margin_top' => 48,
            'margin_bottom' => 25,
            'margin_header' => 10,
            'margin_footer' => 10
        ]);
        $mpdf->SetProtection(array('print'));
        $mpdf->SetTitle($company->name." - Relación de Facturas ".$eps->alias);
        $mpdf->SetAuthor($company->name);
        // $mpdf->SetWatermarkText("Paid");
        // $mpdf->showWatermarkText = true;
        // $mpdf->watermark_font = 'DejaVuSansCondensed';
        // $mpdf->watermarkTextAlpha = 0.1;
        $mpdf->SetDisplayMode('fullpage');
        $mpdf->WriteHTML($html);
        $mpdf->Output("Relación de Facturas ".$eps->alias.'.pdf', 'I');

    }

    public function volume()
    {
        $epss = Eps::all();

        if (!$epss) {
            Session::flash('message_danger', 'No se encontró el listado de EPS');
            return redirect()->back()->withInput();            
        }

        $invoicesAmount = count(Invoice::getInvoicesByEpsId($epss->toArray()[0]['id'], date("Y-m-d"), date("Y-m-d")));
        $epss = $epss->pluck('name', 'id');
        $companies = Company::all()->pluck('name', 'id');

        return view('invoice.volume', compact('epss', 'companies', 'invoicesAmount'));
    }

    public function volumePDF(Request $request) 
    {
        $epsId = $request->get('eps_id');
        $initialDate = $request->get('initial_date');
        $finalDate = $request->get('final_date');
        $companyId = $request->get('company_id');

        $invoices = Invoice::getInvoicesByEpsId($epsId, $initialDate, $finalDate);

        if (count($invoices) == 0) {
            Session::flash('message_danger', 'No hay facturas disponibles para el rango de fecha seleccionado');
            return redirect()->back();
        }

        $company = Company::find($companyId);
        $eps = Eps::find($epsId);

        $html = \View::make('invoice.pdf_volume', compact('invoices', 'company', 'eps', 'initialDate', 'finalDate'));
        $mpdf = new \Mpdf\Mpdf([
            'margin_left' => 20,
            'margin_right' => 15,
            'margin_top' => 48,
            'margin_bottom' => 25,
            'margin_header' => 10,
            'margin_footer' => 10
        ]);
        $mpdf->SetProtection(array('print'));
        $mpdf->SetTitle($company->name." - Volumen de Facturas ".$eps->alias);
        $mpdf->SetAuthor($company->name);
        $mpdf->SetDisplayMode('fullpage');
        $mpdf->WriteHTML($html);
        $mpdf->Output("Volumen de Facturas ".$eps->alias.'.pdf', 'I');

    }

}
