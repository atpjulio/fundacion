<?php

namespace App\Http\Controllers;

use App\Authorization;
use App\City;
use App\Eps;
use App\EpsService;
use App\Http\Requests\ConfirmAuthorizationRequest;
use App\Http\Requests\UpdateAuthorizationRequest;
use App\Patient;
use App\State;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Maatwebsite\Excel\Facades\Excel;
use App\AuthorizationService;
use App\Invoice;

class AuthorizationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (session()->has('authorization-create')) {
            session()->forget('authorization-create');
        }

        $total = Authorization::fullCount();
        $authorizations = Authorization::full();

        return view('authorization.index', compact('total', 'authorizations'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (session()->has('authorization-create')) {
            session()->forget('authorization-create');
        }
        $epss = Eps::all();
        $initialEpsId = $epss->toArray()[0]['id'];
        $services = EpsService::getServices($initialEpsId)->pluck('name', 'id');
        $epss = $epss->pluck('name', 'id');
        $patients = Patient::searchRecords('');

        return view('authorization.create', compact('epss', 'services', 'patients', 'initialEpsId'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function store(ConfirmAuthorizationRequest $request)
    {
        Authorization::storeRecord($request);

        Session::flash('message', 'Autorización guardada exitosamente');
        return redirect()->route('authorization.open');
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
        $epss = Eps::all();
        $authorization = Authorization::find($id);
        $services = EpsService::getServices($authorization->eps_id);
        $epss = $epss->pluck('name', 'id');
        $patients = Patient::searchRecords($authorization->patient->dni);
        $code = $authorization->codec;
        $dateFrom = $authorization->date_from;
        $dateTo = $authorization->date_to;
        $initialEpsId = $authorization->eps_id;

        return view('authorization.edit', compact('epss', 'services', 'patients', 'authorization', 'code', 'dateFrom', 'dateTo', 'initialEpsId'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateAuthorizationRequest $request, $id)
    {
        $authorization = Authorization::updateRecord($request);

        Session::flash('message', 'Autorización actualizada exitosamente');

        if ($authorization->invoice_id > 0) {
            return redirect()->route('authorization.index');
        }
        return redirect()->route('authorization.open');
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
            $authorization = Authorization::find($id);
            $code = $authorization->code;

            $authorization->delete();
            Session::flash('message', 'Autorización eliminada exitosamente');

            if (strpos($code, config('constants.unathorized.prefix')) !== false) {
                return redirect()->route('authorization.index');
            }
            return redirect()->route('authorization.incomplete');
        }
        Session::flash('message_danger', 'No tienes permiso para borrar autorizaciones. Este movimiento ha sido notificado');
        return redirect()->route('authorization.index');
    }

    public function confirm(ConfirmAuthorizationRequest $request)
    {
        $eps = Eps::find($request->get('eps_id'));
        $service = EpsService::find($request->get('eps_service_id'));
        $patient = Patient::find($request->get('patient_id'));
        $code = $request->get('code');
        $dateFrom = $request->get('date_from');
        $dateTo = $request->get('date_to');
        $notes = $request->get('notes');
        $show = true;

        return view('authorization.confirmation', compact('eps', 'service', 'patient', 'code', 'dateFrom', 'dateTo', 'notes', 'show'));
    }

    public function createBack(Request $request)
    {
        dd("under construction");
    }

    public function excel($id)
    {
        $authorization = Authorization::find($id);
        if (!$authorization) {
            Session::flash('message_danger', 'No se pudo crear planilla. Por favor intenta nuevamente');
            return redirect()->route('authorization.index');
        }

        Excel::load('public/files/hospedaje.xls', function($excel) use ($authorization) {

            $monthDiff = intval(substr($authorization->date_to, 5, 2)) - intval(substr($authorization->date_from, 5, 2));

            for ($i = 0; $i <= $monthDiff; $i++) {
                $excel->sheet($i, function($sheet) use ($authorization, $i, $monthDiff) {
                    $sheet->cell('B10', function($cell) use ($authorization) {
                        $cell->setValue($authorization->patient->full_name);
                    });

                    if ($authorization->companion) {
                        $sheet->cell('B11', function($cell) use ($authorization) {
                            $cell->setValue($authorization->companion_name ?: (count($authorization->companions) > 0 ? $authorization->companions[0]->name : ''));
                        });
                        $sheet->cell('F11', function($cell) use ($authorization) {
                            $cell->setValue('CC - '.(!empty($authorization->companion_dni) ? $authorization->companion_dni : (count($authorization->companions) > 0 ? $authorization->companions[0]->dni : '')));
                        });
                    }

                    $sheet->cell('F10', function($cell) use ($authorization) {
                        $cell->setValue($authorization->patient->dni_type.' - '.$authorization->patient->dni);
                    });

                    if ($monthDiff > 0) {
                        if ($i == $monthDiff) {
                            $sheet->cell('I10', function($cell) use ($authorization) {
                                $cell->setValue(\Carbon\Carbon::parse($authorization->date_to)
                                    ->startOfMonth()->format("d"));
                            });
                            $sheet->cell('J10', function($cell) use ($authorization) {
                                $cell->setValue(\Carbon\Carbon::parse($authorization->date_to)
                                    ->startOfMonth()->format("m"));
                            });
                            $sheet->cell('K10', function($cell) use ($authorization) {
                                $cell->setValue(\Carbon\Carbon::parse($authorization->date_to)
                                    ->startOfMonth()->format("Y"));
                            });
                            $sheet->cell('I11', function($cell) use ($authorization) {
                                $cell->setValue(\Carbon\Carbon::parse($authorization->date_to)->format("d"));
                            });
                            $sheet->cell('J11', function($cell) use ($authorization) {
                                $cell->setValue(\Carbon\Carbon::parse($authorization->date_to)->format("m"));
                            });
                            $sheet->cell('K11', function($cell) use ($authorization) {
                                $cell->setValue(\Carbon\Carbon::parse($authorization->date_to)->format("Y"));
                            });
                        } else {
                            $sheet->cell('I10', function($cell) use ($authorization) {
                                $cell->setValue(\Carbon\Carbon::parse($authorization->date_from)->format("d"));
                            });
                            $sheet->cell('J10', function($cell) use ($authorization) {
                                $cell->setValue(\Carbon\Carbon::parse($authorization->date_from)->format("m"));
                            });
                            $sheet->cell('K10', function($cell) use ($authorization) {
                                $cell->setValue(\Carbon\Carbon::parse($authorization->date_from)->format("Y"));
                            });
                            $sheet->cell('I11', function($cell) use ($authorization) {
                                $cell->setValue(\Carbon\Carbon::parse($authorization->date_from)
                                    ->endOfMonth()->format("d"));
                            });
                            $sheet->cell('J11', function($cell) use ($authorization) {
                                $cell->setValue(\Carbon\Carbon::parse($authorization->date_from)
                                    ->endOfMonth()->format("m"));
                            });
                            $sheet->cell('K11', function($cell) use ($authorization) {
                                $cell->setValue(\Carbon\Carbon::parse($authorization->date_from)
                                    ->endOfMonth()->format("Y"));
                            });
                        }
                    } else {
                        $sheet->cell('I10', function($cell) use ($authorization) {
                            $cell->setValue(\Carbon\Carbon::parse($authorization->date_from)->format("d"));
                        });
                        $sheet->cell('J10', function($cell) use ($authorization) {
                            $cell->setValue(\Carbon\Carbon::parse($authorization->date_from)->format("m"));
                        });
                        $sheet->cell('K10', function($cell) use ($authorization) {
                            $cell->setValue(\Carbon\Carbon::parse($authorization->date_from)->format("Y"));
                        });
                        $sheet->cell('I11', function($cell) use ($authorization) {
                            $cell->setValue(\Carbon\Carbon::parse($authorization->date_to)->format("d"));
                        });
                        $sheet->cell('J11', function($cell) use ($authorization) {
                            $cell->setValue(\Carbon\Carbon::parse($authorization->date_to)->format("m"));
                        });
                        $sheet->cell('K11', function($cell) use ($authorization) {
                            $cell->setValue(\Carbon\Carbon::parse($authorization->date_to)->format("Y"));
                        });
                    }

                    $sheet->cell('B14', function($cell) use ($authorization) {
                        $cell->setValue(City::getCityByCodeAndState($authorization->patient->state, $authorization->patient->city));
                    });
                    $sheet->cell('B15', function($cell) use ($authorization) {
                        $cell->setValue(State::getStateByCode($authorization->patient->state));
                    });
                    $sheet->cell('B16', function($cell) use ($authorization) {
                        $cell->setValue($authorization->diagnosis);
                    });
                    $sheet->cell('B17', function($cell) use ($authorization) {
                        $cell->setValue($authorization->eps->alias);
                    });
                    $sheet->cell('K2', function($cell) use ($authorization) {
                        $cell->setValue($authorization->codec ?: '');
                    });
                    $sheet->cell('G14', function($cell) use ($authorization) {
                        $cell->setValue($authorization->patient->phone ? $authorization->patient->phone->phone : '');
                    });
                    $sheet->cell('G16', function($cell) use ($authorization) {
                        $cell->setValue($authorization->companion_phone ?: '');
                    });
                    $sheet->cell('J14', function($cell) use ($authorization) {
                        $cell->setValue($authorization->location === 'Hospedaje' ? 'Si' : '');
                    });
                    $sheet->cell('J15', function($cell) use ($authorization) {
                        $cell->setValue($authorization->location === 'Clínica' ? 'Si' : '');
                    });
                    $sheet->cell('J16', function($cell) use ($authorization) {
                        $cell->setValue($authorization->location === 'Unidad UCI' ? 'Si' : '');
                    });
                    $sheet->cell('J17', function($cell) use ($authorization) {
                        $cell->setValue($authorization->location === 'Habitación' ? 'Si' : '');
                    });
                });
            }

            for ($j = 2; $j > $monthDiff; $j--) {
                $excel->removeSheetByIndex($j);
            }
            $excel->setActiveSheetIndex(0);

        })->setFilename('Hospedaje_'.$authorization->eps->alias.'_'.$authorization->code)
        ->export('xls');

    }

    public function incomplete()
    {
        if (session()->has('authorization-create')) {
            session()->forget('authorization-create');
        }

        $authorizations = Authorization::incomplete();

        return view('authorization.incomplete', compact('authorizations'));
    }

    public function open()
    {
        $authorizations = Authorization::open();

        return view('authorization.open', compact('authorizations'));
    }

    public function close()
    {
        $total = Authorization::closeCount();
        $authorizations = Authorization::close();

        return view('authorization.close', compact('total', 'authorizations'));
    }

    public function global(Request $request)
    {
        $authorizations = Authorization::global($request->get('authorization_code'));

        return view('authorization.global', compact('authorizations'));
    }

    public function servicesUpdate(Request $request)
    {
        $authorization = Authorization::findOrFail($request->get('authorization_id'));
        $some = '';

        for ($i = 0; $i < $request->get('services_quantity'); $i++) { 
            if ($request->get('service_days'.$i) == "" or $request->get('service_totals'.$i) == "") {
                continue;
            }
            $bool = AuthorizationService::fixAuthorizationService(
                $authorization, 
                $request->get('service_codes'.$i),
                $request->get('service_days'.$i)
            );
            $some .= $authorization->id.' '.$request->get('service_codes'.$i).' '
                .$request->get('service_days'.$i).' result: '.$bool;            
        }

        $invoice = Invoice::findOrFail($request->get('invoice_id'));
        $invoiceCodes = json_decode($invoice->multiple_codes, true);
        $invoiceDays = json_decode($invoice->multiple_days, true);
        $invoiceTotals = json_decode($invoice->multiple_totals, true);

        foreach ($invoiceCodes as $k => $val) {
            if ($val == $authorization->code) {
                $invoiceDays[$k] = $request->get('service_days0');
                $invoiceTotals[$k] = $authorization->price->daily_price * $request->get('service_days0');
            }
        }

        $invoice->multiple_codes = json_encode($invoiceCodes);
        $invoice->multiple_days = json_encode($invoiceDays);
        $invoice->multiple_totals = json_encode($invoiceTotals);

        $invoice->save();

        return response(json_encode($request->all()).' some -> '.$some, 200);
    }
}
