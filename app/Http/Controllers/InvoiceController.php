<?php

namespace App\Http\Controllers;

use App\AccountingNote;
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
        $total = Invoice::count();
        $invoices = Invoice::searchRecords('');

        return view('invoice.index', compact('invoices', 'total'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $companies = Company::all();//->pluck('name', 'id');
        $lastNumber = Invoice::getLastNumber();
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
        // dd($request->all());
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
        $companies = Company::all();//->pluck('name', 'id');
        $invoice = Invoice::find($id);

        if (!$invoice) {
            Session::flash('message_danger', 'Factura no encontrada');
            return redirect()->back()->withInput();
        }

        $lastNumber = $invoice->number;
        $authorizations = Authorization::openForInvoices();

        $extra = Authorization::checkIfExists($invoice->authorization_code);
        if ($extra) {
            $authorizations->push($extra);
        }

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
        if ($request->get('multiple_codes') == null) {
            Session::flash('message_danger', 'Falta al menos un código de autorización por llenar');
            return redirect()->back()->withInput();
        }

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
        Invoice::updateRecord($request, $id);

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

            if ($invoice->multiple) {
                foreach (json_decode($invoice->multiple_codes, true) as $code) {
                    $authorization = Authorization::findByCode($code);
                    if ($authorization) {
                        $authorization->update([
                            'invoice_id' => 0
                        ]);
                    }
                }
            } else {
                $authorization = Authorization::findByCode($invoice->authorization_code);
                if ($authorization) {
                    $authorization->update([
                        'invoice_id' => 0
                    ]);
                }
            }

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

        // $invoicesAmount = count(Invoice::getInvoicesByEpsId($epss->toArray()[0]['id'], date("Y-m-d"), date("Y-m-d")));
        $invoices = Invoice::getInvoicesByEpsIdNumber($epss->toArray()[0]['id'], 1, 50000);
        $invoicesAmount = count($invoices);
        $initialNumber = 1;
        $finalNumber = 1;

        if ($invoices) {
            $initialNumber = $invoices->first()->number;
            $finalNumber = $invoices->last()->number;
        }

        $epss = $epss->pluck('name', 'id');
        $companies = Company::all()->pluck('name', 'id');

        return view('invoice.relation', compact('epss', 'companies', 'invoicesAmount', 'initialNumber', 'finalNumber'));
    }

    public function relationPDF(Request $request)
    {
        $epsId = $request->get('eps_id');
        // $initialDate = $request->get('initial_date');
        // $finalDate = $request->get('final_date');
        $initialNumber = $request->get('initial_number');
        $finalNumber = $request->get('final_number');
        $companyId = $request->get('company_id');
        $createdAt = $request->get('created_at');

        // $invoices = Invoice::getInvoicesByEpsId($epsId, $initialDate, $finalDate);
        $invoices = Invoice::getInvoicesByEpsIdNumber($epsId, $initialNumber, $finalNumber);

        if (count($invoices) == 0) {
            Session::flash('message_danger', 'No hay facturas disponibles para el rango de fecha seleccionado');
            return redirect()->back();
        }

        $initialDate = $invoices->first()->created_at;
        $finalDate = $invoices->last()->created_at;
        $company = Company::find($companyId);
        $eps = Eps::find($epsId);

        $html = \View::make('invoice.pdf_relation', compact('invoices', 'company', 'eps', 'initialDate', 'finalDate', 'createdAt'));
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

        // $invoicesAmount = count(Invoice::getInvoicesByEpsId($epss->toArray()[0]['id'], date("Y-m-d"), date("Y-m-d")));
        $invoices = Invoice::getInvoicesByEpsIdNumber($epss->toArray()[0]['id'], 1, 50000);
        $invoicesAmount = count($invoices);
        $initialNumber = 1;
        $finalNumber = 1;

        if ($invoices) {
            $initialNumber = $invoices->first()->number;
            $finalNumber = $invoices->last()->number;
        }

        $epss = $epss->pluck('name', 'id');
        $companies = Company::all()->pluck('name', 'id');

        return view('invoice.volume', compact('epss', 'companies', 'invoicesAmount', 'initialNumber', 'finalNumber'));
    }

    public function volumePDF(Request $request)
    {
        $epsId = $request->get('eps_id');
        // $initialDate = $request->get('initial_date');
        // $finalDate = $request->get('final_date');
        $initialNumber = $request->get('initial_number');
        $finalNumber = $request->get('final_number');        
        $companyId = $request->get('company_id');
        $createdAt = $request->get('created_at');

        // $invoices = Invoice::getInvoicesByEpsId($epsId, $initialDate, $finalDate);
        $invoices = Invoice::getInvoicesByEpsIdNumber($epsId, $initialNumber, $finalNumber);

        if (count($invoices) == 0) {
            Session::flash('message_danger', 'No hay facturas disponibles para el rango de fecha seleccionado');
            return redirect()->back();
        }

        $initialDate = $invoices->first()->created_at;
        $finalDate = $invoices->last()->created_at;        
        $company = Company::find($companyId);
        $eps = Eps::find($epsId);

        ini_set("pcre.backtrack_limit", "5000000");
        $html = \View::make('invoice.pdf_volume', compact('invoices', 'company', 'eps', 'initialDate', 'finalDate', 'createdAt'));
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

    public function import()
    {

    }

    public function importProcess(Request $request)
    {
        $file = $request->file('txt_file');
        $counter = 0;

        $fileResource  = fopen($file, "r");
        if ($fileResource) {
            while (($line = fgets($fileResource)) !== false) {
                if (strpos($line, "Factura") === false) {
                    if (Invoice::storeRecordFromTxt($line)) {
                        $counter++;
                    }
                }
            }
            fclose($fileResource);
        }

        if ($counter > 0) {
            Session::flash("message", "Se guardaron $counter facturas exitosamente!");
        } else {
            Session::flash("message_warning", "No se guardó ningún factura. Es posible que ya estén guardadas en el sistema");
        }
        return redirect()->route('invoice.import');
    }

    public function delete($id)
    {
        $invoice = Invoice::find($id);
        if (!$invoice) {
            Session::flash('message_danger', 'No se encontró la factura, por favor inténtalo nuevamente');
            return redirect()->back()->withInput();
        }

        return view('invoice.delete_modal', compact('invoice'));
    }

    public function export(Request $request)
    {
        // dd($request->all());
        $epss = Eps::forSelect();
        $companies = Company::forSelect();

        $oldEpsId      = $request->get('eps_id');
        $oldCompanyId  = $request->get('company_id');
        $initialNumber = $request->get('initial_number');
        $finalNumber   = $request->get('final_number');
        $initialDate   = $request->get('initial_date');
        $finalDate     = $request->get('final_date');
        $epsId         = $request->get('eps_id') ?? $epss->keys()->first();
        $companyId     = $request->get('company_id') ?? $companies->keys()->first();
        $oldSelection  = $request->get('selection');
        $except        = $request->get('except') ?? array();

        $baseQuery = Invoice::search($epsId, $companyId, $request);

        $selection = (clone $baseQuery)
            ->orderBy('number', 'desc')
            ->pluck('number', 'id');

        $query = (clone $baseQuery);

        if ($oldSelection) {
            $query->whereIn('id', $oldSelection);
        }

        if ($request->get('export') != null) {
            return Invoice::export($request, clone $query);
        }

        $invoices = $query->orderBy('number', 'desc')
            ->paginate(config('constants.pagination'));

        return view('invoice.export', compact(
            'epss', 'companies', 'initialNumber', 'finalNumber', 'initialDate', 'finalDate', 
            'selection', 'oldSelection', 'oldEpsId', 'oldCompanyId', 'invoices', 'except'
        ));
    }
}
