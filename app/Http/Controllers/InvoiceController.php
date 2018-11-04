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
        $authorizations = Authorization::full();

        return view('invoice.create', compact('companies', 'lastNumber', 'authorizations'));
//        $epss = Eps::all();
//        $initialEpsId = $epss->toArray()[0]['id'];
//        $services = EpsService::getServices($initialEpsId)->pluck('name', 'id');
//        $epss = $epss->pluck('name', 'id');
//        $patients = Patient::all();
//
//        return view('invoice.create', compact('epss', 'services', 'patients', 'initialEpsId'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreInvoiceRequest $request)
    {
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
        $lastNumber = $invoice->number;
        $authorizations = Authorization::full();

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

            $invoice->delete();

            Session::flash('message', 'Factura eliminada exitosamente');
            return redirect()->route('invoice.index');
        }
        Session::flash('message_danger', 'No tienes permiso para borrar facturas. Este movimiento ha sido notificado');
        return redirect()->route('invoice.index');
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
        $invoice = Invoice::find($id);

        //dd($invoice->authorization->service);
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
        $mpdf->SetTitle($invoice->company->name." - Factura ".sprintf("%05d", $invoice->number));
        $mpdf->SetAuthor($invoice->company->name);
        // $mpdf->SetWatermarkText("Paid");
        // $mpdf->showWatermarkText = true;
        // $mpdf->watermark_font = 'DejaVuSansCondensed';
        // $mpdf->watermarkTextAlpha = 0.1;
        $mpdf->SetDisplayMode('fullpage');
        $mpdf->WriteHTML($html);
        $mpdf->Output('Factura No '.sprintf("%05d", $invoice->number).'.pdf', 'I');

    }

    public function relation()
    {
        $epss = Eps::all();
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

}
