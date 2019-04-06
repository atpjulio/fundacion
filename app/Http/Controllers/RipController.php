<?php

namespace App\Http\Controllers;

use App\Company;
use App\Eps;
use App\Http\Requests\StoreRipRequest;
use App\Http\Requests\UpdateRipRequest;
use App\Invoice;
use App\Rip;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

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

        return view('accounting.rip.create', compact('epss', 'companies', 'invoicesAmount', 'initialNumber', 'finalNumber'));
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
    }

    public function test() 
    {
        Excel::create('RIPS_test', function($excel) {
 
            $excel->sheet('US', function($sheet) {
                $sheet->setHeight(1, 50);
                $sheet->getStyle('A1:N1')->getAlignment()->setWrapText(true);
                $sheet->getStyle('A1:N1')->getFont()->setSize(10);                

                $sheet->setWidth('A', 8);
                $sheet->cell('A1', function($cell) {
                    $cell->setValue('Tipo de Identificación del Usuario');
                    $cell->setFontColor('#0000FF');
                    $cell->setAlignment('center');
                });

                $sheet->setWidth('B', 11);
                $sheet->cell('B1', function($cell) {
                    $cell->setValue('Número de Identificación del Usuario en el Sistema');   
                    $cell->setFontColor('#0000FF');
                    $cell->setAlignment('center');
                });

                $sheet->setWidth('C', 12);
                $sheet->cell('C1', function($cell) {
                    $cell->setValue('Código Entidad Administradora');   
                    $cell->setFontColor('#0000FF');
                    $cell->setAlignment('center');
                });
                $sheet->setWidth('D', 8);
                $sheet->cell('D1', function($cell) {
                    $cell->setValue('Tipo de usuario');   
                    $cell->setFontColor('#0000FF');
                    $cell->setAlignment('center');
                });

                $sheet->setWidth('E', 9);
                $sheet->cell('E1', function($cell) {
                    $cell->setValue('Primer apellido del usuario');   
                    $cell->setFontColor('#0000FF');
                    $cell->setAlignment('center');
                });

                $sheet->setWidth('F', 9);
                $sheet->cell('F1', function($cell) {
                    $cell->setValue('Segundo apellido del usuario');   
                    $cell->setFontColor('#0000FF');
                    $cell->setAlignment('center');
                });
                $sheet->setWidth('G', 9);
                $sheet->cell('G1', function($cell) {
                    $cell->setValue('Primer nombre del usuario');   
                    $cell->setFontColor('#0000FF');
                    $cell->setAlignment('center');
                });

                $sheet->setWidth('H', 9);
                $sheet->cell('H1', function($cell) {
                    $cell->setValue('Segundo nombre del usuario');   
                    $cell->setFontColor('#0000FF');
                    $cell->setAlignment('center');
                });
                $sheet->setWidth('I', 8);
                $sheet->cell('I1', function($cell) {
                    $cell->setValue('Edad');   
                    $cell->setFontColor('#0000FF');
                    $cell->setAlignment('center');
                });

                $sheet->setWidth('J', 6);
                $sheet->cell('J1', function($cell) {
                    $cell->setValue('Unidad de medida de la Edad');   
                    $cell->setFontColor('#0000FF');
                    $cell->setAlignment('center');
                });
                $sheet->setWidth('K', 8);
                $sheet->cell('K1', function($cell) {
                    $cell->setValue('Sexo');   
                    $cell->setFontColor('#0000FF');
                    $cell->setAlignment('center');
                });

                $sheet->setWidth('L', 11);
                $sheet->cell('L1', function($cell) {
                    $cell->setValue('Código del departamento de residencia habitual');   
                    $cell->setFontColor('#0000FF');
                    $cell->setAlignment('center');
                });
                $sheet->setWidth('M', 8);
                $sheet->cell('M1', function($cell) {
                    $cell->setValue('Código de municipios de residencia habitual');   
                    $cell->setFontColor('#0000FF');
                    $cell->setAlignment('center');
                });

                $sheet->setWidth('N', 7);
                $sheet->cell('N1', function($cell) {
                    $cell->setValue('Zona de residencia habitual');   
                    $cell->setFontColor('#0000FF');
                    $cell->setAlignment('center');
                });

            });
            $excel->sheet('AF', function($sheet) {
                $sheet->setHeight(1, 50);
                $sheet->getStyle('A1:Q1')->getAlignment()->setWrapText(true);
                $sheet->getStyle('A1:Q1')->getFont()->setSize(10);                

                $sheet->setWidth('A', 11);
                $sheet->cell('A1', function($cell) {
                    $cell->setValue('Código del Prestador');
                    $cell->setFontColor('#0000FF');
                    $cell->setAlignment('center');
                });

                $sheet->setWidth('B', 35);
                $sheet->cell('B1', function($cell) {
                    $cell->setValue('Razón Social o Apellidos y Nombres del prestador');   
                    $cell->setFontColor('#0000FF');
                    $cell->setAlignment('center');
                });

                $sheet->setWidth('C', 10);
                $sheet->cell('C1', function($cell) {
                    $cell->setValue('Tipo de Identificación');   
                    $cell->setFontColor('#0000FF');
                    $cell->setAlignment('center');
                });
                $sheet->setWidth('D', 10);
                $sheet->cell('D1', function($cell) {
                    $cell->setValue('Número de Identificación');   
                    $cell->setFontColor('#0000FF');
                    $cell->setAlignment('center');
                });

                $sheet->setWidth('E', 10);
                $sheet->cell('E1', function($cell) {
                    $cell->setValue('Número de la factura');   
                    $cell->setFontColor('#0000FF');
                    $cell->setAlignment('center');
                });

                $sheet->setWidth('F', 10);
                $sheet->cell('F1', function($cell) {
                    $cell->setValue('Fecha de expedición de la factura');   
                    $cell->setFontColor('#0000FF');
                    $cell->setAlignment('center');
                });
                $sheet->setWidth('G', 10);
                $sheet->cell('G1', function($cell) {
                    $cell->setValue('Fecha de Inicio');   
                    $cell->setFontColor('#0000FF');
                    $cell->setAlignment('center');
                });

                $sheet->setWidth('H', 10);
                $sheet->cell('H1', function($cell) {
                    $cell->setValue('Fecha final');   
                    $cell->setFontColor('#0000FF');
                    $cell->setAlignment('center');
                });
                $sheet->setWidth('I', 10);
                $sheet->cell('I1', function($cell) {
                    $cell->setValue('Código entidad Administradora');   
                    $cell->setFontColor('#0000FF');
                    $cell->setAlignment('center');
                });

                $sheet->setWidth('J', 10);
                $sheet->cell('J1', function($cell) {
                    $cell->setValue('Nombre entidad administradora');   
                    $cell->setFontColor('#0000FF');
                    $cell->setAlignment('center');
                });
                $sheet->setWidth('K', 10);
                $sheet->cell('K1', function($cell) {
                    $cell->setValue('Número del Contrato');   
                    $cell->setFontColor('#0000FF');
                    $cell->setAlignment('center');
                });

                $sheet->setWidth('L', 10);
                $sheet->cell('L1', function($cell) {
                    $cell->setValue('Plan de Beneficios');   
                    $cell->setFontColor('#0000FF');
                    $cell->setAlignment('center');
                });
                $sheet->setWidth('M', 10);
                $sheet->cell('M1', function($cell) {
                    $cell->setValue('Número de la póliza');   
                    $cell->setFontColor('#0000FF');
                    $cell->setAlignment('center');
                });

                $sheet->setWidth('N', 10);
                $sheet->cell('N1', function($cell) {
                    $cell->setValue('Valor total del pago compartido COPAGO');   
                    $cell->setFontColor('#0000FF');
                    $cell->setAlignment('center');
                });

                $sheet->setWidth('O', 10);
                $sheet->cell('O1', function($cell) {
                    $cell->setValue('Valor de la comisión');   
                    $cell->setFontColor('#0000FF');
                    $cell->setAlignment('center');
                });

                $sheet->setWidth('P', 10);
                $sheet->cell('P1', function($cell) {
                    $cell->setValue('Valor total de Descuentos');   
                    $cell->setFontColor('#0000FF');
                    $cell->setAlignment('center');
                });

                $sheet->setWidth('Q', 10);
                $sheet->cell('Q1', function($cell) {
                    $cell->setValue('Valor Neto a Pagar por la entidad Contratante');   
                    $cell->setFontColor('#0000FF');
                    $cell->setAlignment('center');
                });
            });
            $excel->sheet('AT', function($sheet) {
                $sheet->setHeight(1, 50);
                $sheet->getStyle('A1:K1')->getAlignment()->setWrapText(true);
                $sheet->getStyle('A1:K1')->getFont()->setSize(10);                

                $sheet->setWidth('A', 8);
                $sheet->cell('A1', function($cell) {
                    $cell->setValue('Número de la factura');
                    $cell->setFontColor('#0000FF');
                    $cell->setAlignment('center');
                });

                $sheet->setWidth('B', 12);
                $sheet->cell('B1', function($cell) {
                    $cell->setValue('Código del prestador de servicios de salud');   
                    $cell->setFontColor('#0000FF');
                    $cell->setAlignment('center');
                });

                $sheet->setWidth('C', 10);
                $sheet->cell('C1', function($cell) {
                    $cell->setValue('Tipo de Identificación del Usuario');   
                    $cell->setFontColor('#0000FF');
                    $cell->setAlignment('center');
                });
                $sheet->setWidth('D', 10);
                $sheet->cell('D1', function($cell) {
                    $cell->setValue('Número de identificación del usuario en el sistema');   
                    $cell->setFontColor('#0000FF');
                    $cell->setAlignment('center');
                });

                $sheet->setWidth('E', 17);
                $sheet->cell('E1', function($cell) {
                    $cell->setValue('Número de autorización');   
                    $cell->setFontColor('#0000FF');
                    $cell->setAlignment('center');
                });

                $sheet->setWidth('F', 9);
                $sheet->cell('F1', function($cell) {
                    $cell->setValue('Tipo de servicio');   
                    $cell->setFontColor('#0000FF');
                    $cell->setAlignment('center');
                });
                $sheet->setWidth('G', 9);
                $sheet->cell('G1', function($cell) {
                    $cell->setValue('Código del servicio');   
                    $cell->setFontColor('#0000FF');
                    $cell->setAlignment('center');
                });

                $sheet->setWidth('H', 30);
                $sheet->cell('H1', function($cell) {
                    $cell->setValue('Nombre del servicio');   
                    $cell->setFontColor('#0000FF');
                    $cell->setAlignment('center');
                });
                $sheet->setWidth('I', 8);
                $sheet->cell('I1', function($cell) {
                    $cell->setValue('Cantidad');   
                    $cell->setFontColor('#0000FF');
                    $cell->setAlignment('center');
                });

                $sheet->setWidth('J', 9);
                $sheet->cell('J1', function($cell) {
                    $cell->setValue('Valor unitario del material e insumo');   
                    $cell->setFontColor('#0000FF');
                    $cell->setAlignment('center');
                });
                $sheet->setWidth('K', 9);
                $sheet->cell('K1', function($cell) {
                    $cell->setValue('Valor total del material e insumo');   
                    $cell->setFontColor('#0000FF');
                    $cell->setAlignment('center');
                });

            });
            $excel->sheet('CT', function($sheet) {
                $sheet->setHeight(1, 50);
                $sheet->getStyle('A1:D1')->getAlignment()->setWrapText(true);
                $sheet->getStyle('A1:D1')->getFont()->setSize(10);                

                $sheet->setWidth('A', 12);
                $sheet->cell('A1', function($cell) {
                    $cell->setValue('Código del prestador de servicios de salud');   
                    $cell->setFontColor('#0000FF');
                    $cell->setAlignment('center');
                });

                $sheet->setWidth('B', 9);
                $sheet->cell('B1', function($cell) {
                    $cell->setValue('Fecha de remisión');
                    $cell->setFontColor('#0000FF');
                    $cell->setAlignment('center');
                });

                $sheet->setWidth('C', 9);
                $sheet->cell('C1', function($cell) {
                    $cell->setValue('Código del archivo');   
                    $cell->setFontColor('#0000FF');
                    $cell->setAlignment('center');
                });

                $sheet->setWidth('D', 9);
                $sheet->cell('D1', function($cell) {
                    $cell->setValue('Total de registros');   
                    $cell->setFontColor('#0000FF');
                    $cell->setAlignment('center');
                });

            });
            $excel->sheet('PLANILLA', function($sheet) {
                $sheet->setWidth('C', 15);
                $sheet->setWidth('D', 15);
            });
            $excel->sheet('CONSULTA', function($sheet) {
                $sheet->setHeight(1, 50);
                $sheet->getStyle('A1:Q1')->getAlignment()->setWrapText(true);
                $sheet->getStyle('A1:Q1')->getFont()->setSize(10);                

                $sheet->setWidth('A', 11);
                $sheet->cell('A1', function($cell) {
                    $cell->setValue('Número de la factura');
                    $cell->setFontColor('#0000FF');
                    $cell->setAlignment('center');
                });

                $sheet->setWidth('B', 15);
                $sheet->cell('B1', function($cell) {
                    $cell->setValue('Código del prestador de servicios de salud');   
                    $cell->setFontColor('#0000FF');
                    $cell->setAlignment('center');
                });

                $sheet->setWidth('C', 10);
                $sheet->cell('C1', function($cell) {
                    $cell->setValue('Tipo de Identificación del Usuario');   
                    $cell->setFontColor('#0000FF');
                    $cell->setAlignment('center');
                });

                $sheet->setWidth('D', 10);
                $sheet->cell('D1', function($cell) {
                    $cell->setValue('Número de identificación del usuario en el sistema');   
                    $cell->setFontColor('#0000FF');
                    $cell->setAlignment('center');
                });

                $sheet->setWidth('E', 10);
                $sheet->cell('E1', function($cell) {
                    $cell->setValue('Fecha de la consulta');   
                    $cell->setFontColor('#0000FF');
                    $cell->setAlignment('center');
                });

                $sheet->setWidth('F', 10);
                $sheet->cell('F1', function($cell) {
                    $cell->setValue('Número de Autorización');   
                    $cell->setFontColor('#0000FF');
                    $cell->setAlignment('center');
                });
                $sheet->setWidth('G', 10);
                $sheet->cell('G1', function($cell) {
                    $cell->setValue('Código de consulta');   
                    $cell->setFontColor('#0000FF');
                    $cell->setAlignment('center');
                });

                $sheet->setWidth('H', 10);
                $sheet->cell('H1', function($cell) {
                    $cell->setValue('Finalidad de la consulta');   
                    $cell->setFontColor('#0000FF');
                    $cell->setAlignment('center');
                });
                $sheet->setWidth('I', 10);
                $sheet->cell('I1', function($cell) {
                    $cell->setValue('Causa externa');   
                    $cell->setFontColor('#0000FF');
                    $cell->setAlignment('center');
                });

                $sheet->setWidth('J', 10);
                $sheet->cell('J1', function($cell) {
                    $cell->setValue('Código del Diagnóstico principal');   
                    $cell->setFontColor('#0000FF');
                    $cell->setAlignment('center');
                });
                $sheet->setWidth('K', 10);
                $sheet->cell('K1', function($cell) {
                    $cell->setValue('Código del diagnóstico relacionado N° 1');   
                    $cell->setFontColor('#0000FF');
                    $cell->setAlignment('center');
                });

                $sheet->setWidth('L', 10);
                $sheet->cell('L1', function($cell) {
                    $cell->setValue('Código del diagnóstico relacionado N° 2');   
                    $cell->setFontColor('#0000FF');
                    $cell->setAlignment('center');
                });
                $sheet->setWidth('M', 10);
                $sheet->cell('M1', function($cell) {
                    $cell->setValue('Código del diagnóstico relacionado N° 3');   
                    $cell->setFontColor('#0000FF');
                    $cell->setAlignment('center');
                });

                $sheet->setWidth('N', 10);
                $sheet->cell('N1', function($cell) {
                    $cell->setValue('Tipo de diagnóstico principal');   
                    $cell->setFontColor('#0000FF');
                    $cell->setAlignment('center');
                });

                $sheet->setWidth('O', 10);
                $sheet->cell('O1', function($cell) {
                    $cell->setValue('Valor de la consulta');   
                    $cell->setFontColor('#0000FF');
                    $cell->setAlignment('center');
                });

                $sheet->setWidth('P', 10);
                $sheet->cell('P1', function($cell) {
                    $cell->setValue('Valor de la cuota moderadora');   
                    $cell->setFontColor('#0000FF');
                    $cell->setAlignment('center');
                });

                $sheet->setWidth('Q', 10);
                $sheet->cell('Q1', function($cell) {
                    $cell->setValue('Valor Neto a Pagar');   
                    $cell->setFontColor('#0000FF');
                    $cell->setAlignment('center');
                });
            });
            $excel->sheet('PROCEDIMIENTOS', function($sheet) {
                $sheet->setHeight(1, 50);
                $sheet->getStyle('A1:O1')->getAlignment()->setWrapText(true);
                $sheet->getStyle('A1:O1')->getFont()->setSize(10);                

                $sheet->setWidth('A', 11);
                $sheet->cell('A1', function($cell) {
                    $cell->setValue('Número de la factura');
                    $cell->setFontColor('#0000FF');
                    $cell->setAlignment('center');
                });

                $sheet->setWidth('B', 15);
                $sheet->cell('B1', function($cell) {
                    $cell->setValue('Código del prestador de servicios de salud');   
                    $cell->setFontColor('#0000FF');
                    $cell->setAlignment('center');
                });

                $sheet->setWidth('C', 10);
                $sheet->cell('C1', function($cell) {
                    $cell->setValue('Tipo de Identificación del Usuario');   
                    $cell->setFontColor('#0000FF');
                    $cell->setAlignment('center');
                });
                $sheet->setWidth('D', 10);
                $sheet->cell('D1', function($cell) {
                    $cell->setValue('Número de identificación del usuario en el sistema');   
                    $cell->setFontColor('#0000FF');
                    $cell->setAlignment('center');
                });

                $sheet->setWidth('E', 10);
                $sheet->cell('E1', function($cell) {
                    $cell->setValue('Fecha de procedmiento');   
                    $cell->setFontColor('#0000FF');
                    $cell->setAlignment('center');
                });

                $sheet->setWidth('F', 10);
                $sheet->cell('F1', function($cell) {
                    $cell->setValue('Número de Autorización');   
                    $cell->setFontColor('#0000FF');
                    $cell->setAlignment('center');
                });
                $sheet->setWidth('G', 10);
                $sheet->cell('G1', function($cell) {
                    $cell->setValue('Código del procedimiento');   
                    $cell->setFontColor('#0000FF');
                    $cell->setAlignment('center');
                });

                $sheet->setWidth('H', 10);
                $sheet->cell('H1', function($cell) {
                    $cell->setValue('Ambito de realización del procedimiento');   
                    $cell->setFontColor('#0000FF');
                    $cell->setAlignment('center');
                });
                $sheet->setWidth('I', 10);
                $sheet->cell('I1', function($cell) {
                    $cell->setValue('Finalidad del procedimiento');   
                    $cell->setFontColor('#0000FF');
                    $cell->setAlignment('center');
                });

                $sheet->setWidth('J', 10);
                $sheet->cell('J1', function($cell) {
                    $cell->setValue('Personal que atiende');   
                    $cell->setFontColor('#0000FF');
                    $cell->setAlignment('center');
                });
                $sheet->setWidth('K', 10);
                $sheet->cell('K1', function($cell) {
                    $cell->setValue('Diagnóstico principal');   
                    $cell->setFontColor('#0000FF');
                    $cell->setAlignment('center');
                });

                $sheet->setWidth('L', 10);
                $sheet->cell('L1', function($cell) {
                    $cell->setValue('Código del diagnóstico relacionado');   
                    $cell->setFontColor('#0000FF');
                    $cell->setAlignment('center');
                });
                $sheet->setWidth('M', 10);
                $sheet->cell('M1', function($cell) {
                    $cell->setValue('Código del diagnóstico de la Complicación');   
                    $cell->setFontColor('#0000FF');
                    $cell->setAlignment('center');
                });

                $sheet->setWidth('N', 10);
                $sheet->cell('N1', function($cell) {
                    $cell->setValue('Forma de realización del acto quirúrgico');   
                    $cell->setFontColor('#0000FF');
                    $cell->setAlignment('center');
                });

                $sheet->setWidth('O', 10);
                $sheet->cell('O1', function($cell) {
                    $cell->setValue('Valor del procedimiento');   
                    $cell->setFontColor('#0000FF');
                    $cell->setAlignment('center');
                });
            });
            $excel->sheet('URGENCIAS', function($sheet) {
                $sheet->setHeight(1, 50);
                $sheet->getStyle('A1:Q1')->getAlignment()->setWrapText(true);
                $sheet->getStyle('A1:Q1')->getFont()->setSize(10);                

                $sheet->setWidth('A', 11);
                $sheet->cell('A1', function($cell) {
                    $cell->setValue('Número de la factura');
                    $cell->setFontColor('#0000FF');
                    $cell->setAlignment('center');
                });

                $sheet->setWidth('B', 15);
                $sheet->cell('B1', function($cell) {
                    $cell->setValue('Código del prestador de servicios de salud');   
                    $cell->setFontColor('#0000FF');
                    $cell->setAlignment('center');
                });

                $sheet->setWidth('C', 10);
                $sheet->cell('C1', function($cell) {
                    $cell->setValue('Tipo de Identificación del Usuario');   
                    $cell->setFontColor('#0000FF');
                    $cell->setAlignment('center');
                });
                $sheet->setWidth('D', 10);
                $sheet->cell('D1', function($cell) {
                    $cell->setValue('Número de identificación del usuario en el sistema');   
                    $cell->setFontColor('#0000FF');
                    $cell->setAlignment('center');
                });

                $sheet->setWidth('E', 10);
                $sheet->cell('E1', function($cell) {
                    $cell->setValue('Fecha de ingreso del usuario a observación');   
                    $cell->setFontColor('#0000FF');
                    $cell->setAlignment('center');
                });

                $sheet->setWidth('F', 10);
                $sheet->cell('F1', function($cell) {
                    $cell->setValue('Hora de ingreso del usuario a observación');   
                    $cell->setFontColor('#0000FF');
                    $cell->setAlignment('center');
                });
                $sheet->setWidth('G', 10);
                $sheet->cell('G1', function($cell) {
                    $cell->setValue('Número de autorización');   
                    $cell->setFontColor('#0000FF');
                    $cell->setAlignment('center');
                });

                $sheet->setWidth('H', 10);
                $sheet->cell('H1', function($cell) {
                    $cell->setValue('Causa externa');   
                    $cell->setFontColor('#0000FF');
                    $cell->setAlignment('center');
                });
                $sheet->setWidth('I', 10);
                $sheet->cell('I1', function($cell) {
                    $cell->setValue('Código del Diagnóstico a la salida');   
                    $cell->setFontColor('#0000FF');
                    $cell->setAlignment('center');
                });

                $sheet->setWidth('J', 10);
                $sheet->cell('J1', function($cell) {
                    $cell->setValue('Código del diagnóstico relacionado N° 1 a la salida');   
                    $cell->setFontColor('#0000FF');
                    $cell->setAlignment('center');
                });
                $sheet->setWidth('K', 10);
                $sheet->cell('K1', function($cell) {
                    $cell->setValue('Código del diagnóstico relacionado N° 2 a la salida');   
                    $cell->setFontColor('#0000FF');
                    $cell->setAlignment('center');
                });

                $sheet->setWidth('L', 10);
                $sheet->cell('L1', function($cell) {
                    $cell->setValue('Código del diagnóstico relacionado N° 3 a la salida');   
                    $cell->setFontColor('#0000FF');
                    $cell->setAlignment('center');
                });
                $sheet->setWidth('M', 10);
                $sheet->cell('M1', function($cell) {
                    $cell->setValue('Destino del usuario a la salida de observación');   
                    $cell->setFontColor('#0000FF');
                    $cell->setAlignment('center');
                });

                $sheet->setWidth('N', 10);
                $sheet->cell('N1', function($cell) {
                    $cell->setValue('Estado a la salida');   
                    $cell->setFontColor('#0000FF');
                    $cell->setAlignment('center');
                });

                $sheet->setWidth('O', 10);
                $sheet->cell('O1', function($cell) {
                    $cell->setValue('Causa básica de muerte en urgencias');   
                    $cell->setFontColor('#0000FF');
                    $cell->setAlignment('center');
                });

                $sheet->setWidth('P', 10);
                $sheet->cell('P1', function($cell) {
                    $cell->setValue('Fecha de salida del usuario de observación');   
                    $cell->setFontColor('#0000FF');
                    $cell->setAlignment('center');
                });

                $sheet->setWidth('Q', 10);
                $sheet->cell('Q1', function($cell) {
                    $cell->setValue('Hora de salida del usuario de observación');   
                    $cell->setFontColor('#0000FF');
                    $cell->setAlignment('center');
                });
            });
            $excel->sheet('MEDICAMENTOS', function($sheet) {
                $sheet->setHeight(1, 50);
                $sheet->getStyle('A1:N1')->getAlignment()->setWrapText(true);
                $sheet->getStyle('A1:N1')->getFont()->setSize(10);                

                $sheet->setWidth('A', 11);
                $sheet->cell('A1', function($cell) {
                    $cell->setValue('Número de la factura');
                    $cell->setFontColor('#0000FF');
                    $cell->setAlignment('center');
                });

                $sheet->setWidth('B', 15);
                $sheet->cell('B1', function($cell) {
                    $cell->setValue('Código del prestador de servicios de salud');   
                    $cell->setFontColor('#0000FF');
                    $cell->setAlignment('center');
                });

                $sheet->setWidth('C', 10);
                $sheet->cell('C1', function($cell) {
                    $cell->setValue('Tipo de Identificación del Usuario');   
                    $cell->setFontColor('#0000FF');
                    $cell->setAlignment('center');
                });
                $sheet->setWidth('D', 10);
                $sheet->cell('D1', function($cell) {
                    $cell->setValue('Número de identificación del usuario en el sistema');   
                    $cell->setFontColor('#0000FF');
                    $cell->setAlignment('center');
                });

                $sheet->setWidth('E', 10);
                $sheet->cell('E1', function($cell) {
                    $cell->setValue('Número de autorización');   
                    $cell->setFontColor('#0000FF');
                    $cell->setAlignment('center');
                });

                $sheet->setWidth('F', 10);
                $sheet->cell('F1', function($cell) {
                    $cell->setValue('Código del medicamento');   
                    $cell->setFontColor('#0000FF');
                    $cell->setAlignment('center');
                });
                $sheet->setWidth('G', 10);
                $sheet->cell('G1', function($cell) {
                    $cell->setValue('Tipo de medicamento');   
                    $cell->setFontColor('#0000FF');
                    $cell->setAlignment('center');
                });

                $sheet->setWidth('H', 10);
                $sheet->cell('H1', function($cell) {
                    $cell->setValue('Nombre genérico del medicamento');   
                    $cell->setFontColor('#0000FF');
                    $cell->setAlignment('center');
                });
                $sheet->setWidth('I', 10);
                $sheet->cell('I1', function($cell) {
                    $cell->setValue('Forma farmaceútica');   
                    $cell->setFontColor('#0000FF');
                    $cell->setAlignment('center');
                });

                $sheet->setWidth('J', 10);
                $sheet->cell('J1', function($cell) {
                    $cell->setValue('Concentración del medicamento');   
                    $cell->setFontColor('#0000FF');
                    $cell->setAlignment('center');
                });
                $sheet->setWidth('K', 10);
                $sheet->cell('K1', function($cell) {
                    $cell->setValue('Unidad de medida del medicamento');   
                    $cell->setFontColor('#0000FF');
                    $cell->setAlignment('center');
                });

                $sheet->setWidth('L', 10);
                $sheet->cell('L1', function($cell) {
                    $cell->setValue('Número de unidades');   
                    $cell->setFontColor('#0000FF');
                    $cell->setAlignment('center');
                });
                $sheet->setWidth('M', 10);
                $sheet->cell('M1', function($cell) {
                    $cell->setValue('Valor unitario del medicamento');   
                    $cell->setFontColor('#0000FF');
                    $cell->setAlignment('center');
                });

                $sheet->setWidth('N', 10);
                $sheet->cell('N1', function($cell) {
                    $cell->setValue('Valor total del medicamento');   
                    $cell->setFontColor('#0000FF');
                    $cell->setAlignment('center');
                });
            });
            $excel->sheet('RECIEN NACIDO', function($sheet) {
                $sheet->setHeight(1, 50);
                $sheet->getStyle('A1:N1')->getAlignment()->setWrapText(true);
                $sheet->getStyle('A1:N1')->getFont()->setSize(10);                

                $sheet->setWidth('A', 11);
                $sheet->cell('A1', function($cell) {
                    $cell->setValue('Número de la factura');
                    $cell->setFontColor('#0000FF');
                    $cell->setAlignment('center');
                });

                $sheet->setWidth('B', 15);
                $sheet->cell('B1', function($cell) {
                    $cell->setValue('Código del prestador de servicios de salud');   
                    $cell->setFontColor('#0000FF');
                    $cell->setAlignment('center');
                });

                $sheet->setWidth('C', 10);
                $sheet->cell('C1', function($cell) {
                    $cell->setValue('Tipo de Identificación del Usuario');   
                    $cell->setFontColor('#0000FF');
                    $cell->setAlignment('center');
                });
                $sheet->setWidth('D', 10);
                $sheet->cell('D1', function($cell) {
                    $cell->setValue('Número de identificación del usuario en el sistema');   
                    $cell->setFontColor('#0000FF');
                    $cell->setAlignment('center');
                });

                $sheet->setWidth('E', 10);
                $sheet->cell('E1', function($cell) {
                    $cell->setValue('Fecha de nacimiento del recién nacido');   
                    $cell->setFontColor('#0000FF');
                    $cell->setAlignment('center');
                });

                $sheet->setWidth('F', 10);
                $sheet->cell('F1', function($cell) {
                    $cell->setValue('Hora de nacimiento');   
                    $cell->setFontColor('#0000FF');
                    $cell->setAlignment('center');
                });
                $sheet->setWidth('G', 10);
                $sheet->cell('G1', function($cell) {
                    $cell->setValue('Edad gestacional');   
                    $cell->setFontColor('#0000FF');
                    $cell->setAlignment('center');
                });

                $sheet->setWidth('H', 10);
                $sheet->cell('H1', function($cell) {
                    $cell->setValue('Control prenatal');   
                    $cell->setFontColor('#0000FF');
                    $cell->setAlignment('center');
                });
                $sheet->setWidth('I', 10);
                $sheet->cell('I1', function($cell) {
                    $cell->setValue('Sexo');   
                    $cell->setFontColor('#0000FF');
                    $cell->setAlignment('center');
                });

                $sheet->setWidth('J', 10);
                $sheet->cell('J1', function($cell) {
                    $cell->setValue('Peso');   
                    $cell->setFontColor('#0000FF');
                    $cell->setAlignment('center');
                });
                $sheet->setWidth('K', 10);
                $sheet->cell('K1', function($cell) {
                    $cell->setValue('Código del diagnóstico del Recién nacido');   
                    $cell->setFontColor('#0000FF');
                    $cell->setAlignment('center');
                });

                $sheet->setWidth('L', 10);
                $sheet->cell('L1', function($cell) {
                    $cell->setValue('Causa básica de muerte');   
                    $cell->setFontColor('#0000FF');
                    $cell->setAlignment('center');
                });
                $sheet->setWidth('M', 10);
                $sheet->cell('M1', function($cell) {
                    $cell->setValue('Fecha de muerte del recién nacido');   
                    $cell->setFontColor('#0000FF');
                    $cell->setAlignment('center');
                });

                $sheet->setWidth('N', 10);
                $sheet->cell('N1', function($cell) {
                    $cell->setValue('Hora de muerte del recién nacido');   
                    $cell->setFontColor('#0000FF');
                    $cell->setAlignment('center');
                });
            });
            $excel->sheet('HOSPITALIZACION', function($sheet) {
                $sheet->setHeight(1, 50);
                $sheet->getStyle('A1:S1')->getAlignment()->setWrapText(true);
                $sheet->getStyle('A1:S1')->getFont()->setSize(10);                

                $sheet->setWidth('A', 11);
                $sheet->cell('A1', function($cell) {
                    $cell->setValue('Número de la factura');
                    $cell->setFontColor('#0000FF');
                    $cell->setAlignment('center');
                });

                $sheet->setWidth('B', 15);
                $sheet->cell('B1', function($cell) {
                    $cell->setValue('Código del prestador de servicios de salud');   
                    $cell->setFontColor('#0000FF');
                    $cell->setAlignment('center');
                });

                $sheet->setWidth('C', 10);
                $sheet->cell('C1', function($cell) {
                    $cell->setValue('Tipo de Identificación del Usuario');   
                    $cell->setFontColor('#0000FF');
                    $cell->setAlignment('center');
                });
                $sheet->setWidth('D', 10);
                $sheet->cell('D1', function($cell) {
                    $cell->setValue('Número de identificación del usuario en el sistema');   
                    $cell->setFontColor('#0000FF');
                    $cell->setAlignment('center');
                });

                $sheet->setWidth('E', 10);
                $sheet->cell('E1', function($cell) {
                    $cell->setValue('Vía de ingreso a la institución');   
                    $cell->setFontColor('#0000FF');
                    $cell->setAlignment('center');
                });

                $sheet->setWidth('F', 10);
                $sheet->cell('F1', function($cell) {
                    $cell->setValue('Fecha de ingreso del usuario a la institución');   
                    $cell->setFontColor('#0000FF');
                    $cell->setAlignment('center');
                });
                $sheet->setWidth('G', 10);
                $sheet->cell('G1', function($cell) {
                    $cell->setValue('Hora de ingreso del usuario a la institución');   
                    $cell->setFontColor('#0000FF');
                    $cell->setAlignment('center');
                });

                $sheet->setWidth('H', 10);
                $sheet->cell('H1', function($cell) {
                    $cell->setValue('Número de autorización');   
                    $cell->setFontColor('#0000FF');
                    $cell->setAlignment('center');
                });
                $sheet->setWidth('I', 10);
                $sheet->cell('I1', function($cell) {
                    $cell->setValue('Causa externa');   
                    $cell->setFontColor('#0000FF');
                    $cell->setAlignment('center');
                });

                $sheet->setWidth('J', 10);
                $sheet->cell('J1', function($cell) {
                    $cell->setValue('Diagnóstico prinicipal de ingreso');   
                    $cell->setFontColor('#0000FF');
                    $cell->setAlignment('center');
                });
                $sheet->setWidth('K', 10);
                $sheet->cell('K1', function($cell) {
                    $cell->setValue('Diagnóstico principal de egreso');   
                    $cell->setFontColor('#0000FF');
                    $cell->setAlignment('center');
                });

                $sheet->setWidth('L', 10);
                $sheet->cell('L1', function($cell) {
                    $cell->setValue('Código del diagnóstico relacionado N° 1 de egreso');   
                    $cell->setFontColor('#0000FF');
                    $cell->setAlignment('center');
                });
                $sheet->setWidth('M', 10);
                $sheet->cell('M1', function($cell) {
                    $cell->setValue('Código del diagnóstico relacionado N° 2 de egreso');   
                    $cell->setFontColor('#0000FF');
                    $cell->setAlignment('center');
                });

                $sheet->setWidth('N', 10);
                $sheet->cell('N1', function($cell) {
                    $cell->setValue('Código del diagnóstico relacionado N° 3 de egreso');   
                    $cell->setFontColor('#0000FF');
                    $cell->setAlignment('center');
                });

                $sheet->setWidth('O', 10);
                $sheet->cell('O1', function($cell) {
                    $cell->setValue('Diagnóstico de la complicación');   
                    $cell->setFontColor('#0000FF');
                    $cell->setAlignment('center');
                });

                $sheet->setWidth('P', 10);
                $sheet->cell('P1', function($cell) {
                    $cell->setValue('Estado a la salida');   
                    $cell->setFontColor('#0000FF');
                    $cell->setAlignment('center');
                });

                $sheet->setWidth('Q', 10);
                $sheet->cell('Q1', function($cell) {
                    $cell->setValue('Diagnóstico de la causa básica de muerte');   
                    $cell->setFontColor('#0000FF');
                    $cell->setAlignment('center');
                });

                $sheet->setWidth('R', 10);
                $sheet->cell('R1', function($cell) {
                    $cell->setValue('Fecha de egreso del usuario a la institución');   
                    $cell->setFontColor('#0000FF');
                    $cell->setAlignment('center');
                });

                $sheet->setWidth('S', 10);
                $sheet->cell('S1', function($cell) {
                    $cell->setValue('Hora de egreso del usuario a la institución');   
                    $cell->setFontColor('#0000FF');
                    $cell->setAlignment('center');
                });
            });

        })->store('xls', storage_path('app/'.config('constants.ripsFiles'))); 
        // ->store('xls', storage_path('excel/exports'));
    }
}
