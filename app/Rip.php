<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class Rip extends Model
{
	use SoftDeletes;

	protected $fillable = [
        'company_id',
        'eps_id',
        'initial_date',
        'final_date',
        'created_at'
	];
    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    /**
     * Relations
     */
    public function eps()
    {
        return $this->hasOne(Eps::class, 'id', 'eps_id');
    }

    /**
     * Methods
     */
    protected function produceRIPS($request)
    {
        $filePrefix = ['AT', 'US', 'AF', 'CT', 'RIPS'];
        $prefixCounter = 0;
        $epsId = $request->get('eps_id');
        // $initialDate = $request->get('initial_date');
        // $finalDate = $request->get('final_date');
        $initialNumber = $request->get('initial_number');
        $finalNumber = $request->get('final_number');

        // $invoices = Invoice::getInvoicesByEpsId($epsId, $initialDate, $finalDate);
        $invoices = Invoice::getInvoicesByEpsIdNumber($epsId, $initialNumber, $finalNumber);

        $rip = null;
        if (count($invoices) <= 0) {
            return $rip;
        }

        $initialDate = $invoices->first()->created_at;
        $finalDate = $invoices->last()->created_at;        

        $lastRip = $this->latest()->first();

        // Creating AT file
        $counterAT = $this->produceAT($invoices, $lastRip ? $lastRip->id + 1 : 1);

        // Creating US file
        $counterUS = $this->produceUS($invoices, $lastRip ? $lastRip->id + 1 : 1);

        // Creating AF file
        $counterAF = $this->produceAF($invoices, $lastRip ? $lastRip->id + 1 : 1);

        // Creating CT file
        $this->produceCT($invoices[0], $lastRip ? $lastRip->id + 1 : 1, $counterUS, $counterAT,
            $counterAF);

        // Creating Excel file
        $this->produceExcel($invoices, $lastRip ? $lastRip->id + 1 : 1, $request);

        $ripPackage = 'RIPS'.sprintf("%06d", $lastRip ? $lastRip->id + 1 : 1).'.zip';
        foreach ($filePrefix as $currentPrefix) {
            $fileName = ($currentPrefix == 'RIPS') ? $currentPrefix.sprintf("%06d", $lastRip ? $lastRip->id + 1 : 1).".xls" : $currentPrefix.sprintf("%06d", $lastRip ? $lastRip->id + 1 : 1).".TXT";
            \Zipper::make(storage_path('app/public/rips/'.$ripPackage))
                ->add(storage_path('app/public/rips/'.$fileName))
                ->close();
        }

        $rip = new Rip();

        $rip->company_id = $request->get('company_id');
        $rip->eps_id = $request->get('eps_id');
        $rip->initial_date = $initialDate;
        $rip->final_date = $finalDate;
        $rip->created_at = $request->get('created_at');
        $rip->url = config('constants.ripsFiles').$ripPackage;

        $rip->save();

        return $rip;
    }

    protected function updateRIPS($request, $id)
    {
        $invoices = Invoice::getInvoicesByEpsId($request->get('eps_id'));
        $rip = $this->find($id);

        if (count($invoices) <= 0 or !$rip) {
            return null;
        }

        $line = "";

        foreach ($invoices as $invoice) {
            $days = \Carbon\Carbon::parse($invoice->authorization->date_to)->diffInDays(\Carbon\Carbon::parse($invoice->authorization->date_from));
            $line .= $invoice->number.",".substr($invoice->company->doc, 0, 9).","
                .$invoice->authorization->patient->dni_type.",".$invoice->authorization->patient->dni.","
                .$invoice->authorization->code.",1,".$invoice->authorization->service->code.","
                .mb_strtoupper($invoice->authorization->service->name).","
                .$days.",".$invoice->eps->daily_price.","
                .floatval($days * $invoice->eps->daily_price)."\r";
        }

        $fileName = "AT".sprintf("%06d", $rip->id).".TXT";

        Storage::delete(config('constants.ripsFiles').$fileName);
        Storage::put(config('constants.ripsFiles').$fileName, $line);

        $rip->company_id = $request->get('company_id');
        $rip->eps_id = $request->get('eps_id');
        $rip->initial_date = $request->get('initial_date');
        $rip->final_date = $request->get('final_date');
        $rip->created_at = $request->get('created_at');
        $rip->url = config('constants.ripsFiles').$fileName;

        $rip->save();

        return $rip;
    }

    protected function produceAT($invoices, $id, $update = false)
    {
        $line = "";
        $counter = 0;
        foreach ($invoices as $invoice) {
            if ($invoice->multiple) {
                foreach (json_decode($invoice->multiple_codes, true) as $key => $value) {
                    $currentAuthorization = Authorization::findByCode($value);
                    if ($currentAuthorization) {
                        $days = json_decode($invoice->multiple_days, true)[$key];
                        $dailyPrice = $currentAuthorization->daily_price;
                        try {
                            $total = floatval(json_decode($invoice->multiple_totals, true)[$key]);
                        } catch(\Exception $e) {
                            dd($invoice, $key);
                        }
                        $line .= $invoice->number.",".substr($invoice->company->doc, 0, 9).","
                            .$currentAuthorization->patient->dni_type.",".$currentAuthorization->patient->dni.","
                            .$currentAuthorization->code.",1,".$currentAuthorization->service->code.","
                            .mb_strtoupper($currentAuthorization->service->name).","
                            .$days.",".number_format($dailyPrice, 2, ".", "").","
                            .number_format($total, 2, ".", "")."\r\n";
                        $counter++;
                    }
                }
            } else {
                $days = $invoice->days;
				$dailyPrice = $invoice->authorization->daily_price;
                $line .= $invoice->number.",".substr($invoice->company->doc, 0, 9).","
                    .$invoice->authorization->patient->dni_type.",".$invoice->authorization->patient->dni.","
                    .$invoice->authorization->code.",1,".$invoice->authorization->service->code.","
                    .mb_strtoupper($invoice->authorization->service->name).","
                    .$days.",".number_format($dailyPrice, 2, ".", "").","
                    .number_format($invoice->total, 2, ".", "")."\r\n";
                $counter++;
            }
        }

        $fileName = "AT".sprintf("%06d", $id).".TXT";

        Storage::put(config('constants.ripsFiles').$fileName, $line);

        return $counter;
    }

    protected function produceUS($invoices, $id, $update = false)
    {
        $line = "";
        $counter = 0;
        $arrPatients = [];
        foreach ($invoices as $invoice) {
            if ($invoice->multiple) {
                foreach (json_decode($invoice->multiple_codes, true) as $key => $value) {
                    $currentAuthorization = Authorization::findByCode($value);
                    if ($currentAuthorization and !in_array($currentAuthorization->patient->id, $arrPatients)) {
                        $arrayFirstName = explode(" ", $currentAuthorization->patient->first_name);
                        $firstName = $arrayFirstName[0].",".(isset($arrayFirstName[1]) ? join(" ", array_slice($arrayFirstName, 1)) : '');
                        $arrayLastName = explode(" ", $currentAuthorization->patient->last_name);
                        $lastName = $arrayLastName[0].",".(isset($arrayLastName[1]) ? join(" ", array_slice($arrayLastName, 1)) : '');

                        $line .= $currentAuthorization->patient->dni_type.",".$currentAuthorization->patient->dni
                            .",".$invoice->eps->code.",".$currentAuthorization->patient->type.","
                            .$lastName.",".$firstName.",".$currentAuthorization->patient->age.",1,"
                            .config('constants.genderShort.'.$currentAuthorization->patient->gender).","
                            .sprintf("%02d", $currentAuthorization->patient->state).","
                            .sprintf("%03d", $currentAuthorization->patient->city).","
                            .$currentAuthorization->patient->zone."\r\n";

                        array_push($arrPatients, $currentAuthorization->patient->id);
                        $counter++;
                    }
                }
            } elseif (!in_array($invoice->authorization->patient->id, $arrPatients)) {
								$arrayFirstName = explode(" ", $invoice->authorization->patient->first_name);
								$firstName = $arrayFirstName[0].",".(isset($arrayFirstName[1]) ? join(" ", array_slice($arrayFirstName, 1)) : '');
								$arrayLastName = explode(" ", $invoice->authorization->patient->last_name);
								$lastName = $arrayLastName[0].",".(isset($arrayLastName[1]) ? join(" ", array_slice($arrayLastName, 1)) : '');

                $line .= $invoice->authorization->patient->dni_type.",".$invoice->authorization->patient->dni
                    .",".$invoice->eps->code.",".$invoice->authorization->patient->type.","
                    .$lastName.",".$firstName.",".$invoice->authorization->patient->age.",1,"
                    .config('constants.genderShort.'.$invoice->authorization->patient->gender).","
                    .$invoice->authorization->patient->state.","
                    .$invoice->authorization->patient->city.","
                    .$invoice->authorization->patient->zone."\r\n";

                array_push($arrPatients, $invoice->authorization->patient->id);
                $counter++;
            }
        }
        $fileName = "US".sprintf("%06d", $id).".TXT";

        Storage::put(config('constants.ripsFiles').$fileName, $line);

        return $counter;
    }

    protected function produceAF($invoices, $id, $update = false)
    {
        $line = "";
        $counter = 0;
        foreach ($invoices as $invoice) {
            $createdAt = \Carbon\Carbon::parse($invoice->created_at)->format("d/m/Y");
            $total = $invoice->multiple ? array_sum(json_decode($invoice->multiple_totals, true)) : $invoice->total;

            $line .= substr($invoice->company->doc, 0, 9).",".mb_strtoupper($invoice->company->name).","
                .$invoice->company->doc_type.",".substr($invoice->company->doc, 0, 9).","
                .$invoice->number.",".$createdAt.",".$createdAt.",".$createdAt.","
                .$invoice->eps->code.",".substr(mb_strtoupper($invoice->eps->name), 0, 30).",,,,"
                ."0.00,0.00,0.00,".number_format($total, 2, ".", "")."\r\n";
            $counter++;
        }

        $fileName = "AF".sprintf("%06d", $id).".TXT";

        Storage::put(config('constants.ripsFiles').$fileName, $line);

        return $counter;
    }

    protected function produceCT($invoice, $id, $counterUS, $counterAT, $counterAF, $update = false)
    {

        $counters = ['AF' => $counterAF, 'US' => $counterUS, 'AT' => $counterAT];
        $line = "";
        foreach ($counters as $type => $counter) {
            $createdAt = \Carbon\Carbon::parse($invoice->created_at)->format("d/m/Y");

            $line .= substr($invoice->company->doc, 0, 9).",".$createdAt.","
                .$type.sprintf("%06d", $id).",".$counter."\r\n";
        }

        $fileName = "CT".sprintf("%06d", $id).".TXT";

        Storage::put(config('constants.ripsFiles').$fileName, $line);
    }

    protected function produceExcel($invoices, $id, $request, $update = false)
    {
        $ripDate = $request->get('created_at');
        $fileName = "RIPS".sprintf("%06d", $id);
        Excel::create($fileName, function($excel) use ($invoices, $ripDate, $id) {

            $counterUS = 0;
            $counterAF = 0;
            $counterAT = 0;
            $excel->getDefaultStyle()->getFont()->setSize(10);

            $excel->sheet('US', function($sheet) use ($invoices, &$counterUS) {
                $sheet->setHeight(1, 50);
                $sheet->getStyle('A1:N1')->getAlignment()->setWrapText(true);

                $sheet->setWidth('A', 10);
                $sheet->cell('A1', function($cell) {
                    $cell->setValue('Tipo de Identificación del Usuario');
                    $cell->setFontColor('#0000FF');
                    $cell->setAlignment('center');
                });

                $sheet->setWidth('B', 13);
                $sheet->cell('B1', function($cell) {
                    $cell->setValue('Número de Identificación del Usuario en el Sistema');
                    $cell->setFontColor('#0000FF');
                    $cell->setAlignment('center');
                });

                $sheet->setWidth('C', 13);
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

                $sheet->setWidth('J', 11);
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

                $sheet->setWidth('L', 14);
                $sheet->cell('L1', function($cell) {
                    $cell->setValue('Código del departamento de residencia habitual');
                    $cell->setFontColor('#0000FF');
                    $cell->setAlignment('center');
                });
                $sheet->setWidth('M', 14);
                $sheet->cell('M1', function($cell) {
                    $cell->setValue('Código de municipios de residencia habitual');
                    $cell->setFontColor('#0000FF');
                    $cell->setAlignment('center');
                });

                $sheet->setWidth('N', 14);
                $sheet->cell('N1', function($cell) {
                    $cell->setValue('Zona de residencia habitual');
                    $cell->setFontColor('#0000FF');
                    $cell->setAlignment('center');
                });
                $counter = 2;
                $arrPatients = [];

                foreach ($invoices as $invoice) {
                    if ($invoice->multiple) {
                        foreach (json_decode($invoice->multiple_codes, true) as $key => $value) {
                            $currentAuthorization = Authorization::findByCode($value);
                            if ($currentAuthorization and !in_array($currentAuthorization->patient->id, $arrPatients)) {
                                $arrayFirstName = explode(" ", $currentAuthorization->patient->first_name);
                                $arrayLastName = explode(" ", $currentAuthorization->patient->last_name);

                                $sheet->cell('A'.$counter, function($cell) use ($currentAuthorization) {
                                    $cell->setValue($currentAuthorization->patient->dni_type);
                                });
                                $sheet->cell('B'.$counter, function($cell) use ($currentAuthorization) {
                                    $cell->setValue($currentAuthorization->patient->dni);
                                });
                                $sheet->cell('C'.$counter, function($cell) use ($invoice) {
                                    $cell->setValue($invoice->eps->code);
                                });
                                $sheet->cell('D'.$counter, function($cell) use ($currentAuthorization) {
                                    $cell->setValue($currentAuthorization->patient->type);
                                });
                                $sheet->cell('E'.$counter, function($cell) use ($arrayLastName) {
                                    $cell->setValue(mb_strtoupper($arrayLastName[0]));
                                });
                                $sheet->cell('F'.$counter, function($cell) use ($arrayLastName) {
																	$cell->setValue(mb_strtoupper(join(" ", array_slice($arrayLastName, 1))));
                                });
                                $sheet->cell('G'.$counter, function($cell) use ($arrayFirstName) {
                                    $cell->setValue(mb_strtoupper($arrayFirstName[0]));
                                });
                                $sheet->cell('H'.$counter, function($cell) use ($arrayFirstName) {
																	$cell->setValue(mb_strtoupper(join(" ", array_slice($arrayFirstName, 1))));
                                });
                                $sheet->cell('I'.$counter, function($cell) use ($currentAuthorization) {
                                    $cell->setValue($currentAuthorization->patient->age);
                                });
                                $sheet->cell('J'.$counter, function($cell) {
                                    $cell->setValue("1");
                                });
                                $sheet->cell('K'.$counter, function($cell) use ($currentAuthorization) {
                                    $cell->setValue(config('constants.genderShort.'.$currentAuthorization->patient->gender));
                                });
                                $sheet->cell('L'.$counter, function($cell) use ($currentAuthorization) {
                                    $cell->setValue(sprintf("%02d", $currentAuthorization->patient->state));
                                });
                                $sheet->cell('M'.$counter, function($cell) use ($currentAuthorization) {
                                    $cell->setValue(sprintf("%03d", $currentAuthorization->patient->city));
                                });
                                $sheet->cell('N'.$counter, function($cell) use ($currentAuthorization) {
                                    $cell->setValue($currentAuthorization->patient->zone);
                                });
                                array_push($arrPatients, $currentAuthorization->patient->id);
                                $counter++;
                                $counterUS++;
                            }
                        }
                    } elseif (!in_array($invoice->authorization->patient->id, $arrPatients)) {
                        $arrayFirstName = explode(" ", $invoice->authorization->patient->first_name);
                        $arrayLastName = explode(" ", $invoice->authorization->patient->last_name);

                        $sheet->cell('A'.$counter, function($cell) use ($invoice) {
                            $cell->setValue($invoice->authorization->patient->dni_type);
                        });
                        $sheet->cell('B'.$counter, function($cell) use ($invoice) {
                            $cell->setValue($invoice->authorization->patient->dni);
                        });
                        $sheet->cell('C'.$counter, function($cell) use ($invoice) {
                            $cell->setValue($invoice->eps->code);
                        });
                        $sheet->cell('D'.$counter, function($cell) use ($invoice) {
                            $cell->setValue($invoice->authorization->patient->type);
                        });
                        $sheet->cell('E'.$counter, function($cell) use ($arrayLastName) {
                            $cell->setValue(mb_strtoupper($arrayLastName[0]));
                        });
                        $sheet->cell('F'.$counter, function($cell) use ($arrayLastName) {
														$cell->setValue(mb_strtoupper(join(" ", array_slice($arrayLastName, 1))));
                        });
                        $sheet->cell('G'.$counter, function($cell) use ($arrayFirstName) {
                            $cell->setValue(mb_strtoupper($arrayFirstName[0]));
                        });
                        $sheet->cell('H'.$counter, function($cell) use ($arrayFirstName) {
														$cell->setValue(mb_strtoupper(join(" ", array_slice($arrayFirstName, 1))));
                        });
                        $sheet->cell('I'.$counter, function($cell) use ($invoice) {
                            $cell->setValue($invoice->authorization->patient->age);
                        });
                        $sheet->cell('J'.$counter, function($cell) {
                            $cell->setValue("1");
                        });
                        $sheet->cell('K'.$counter, function($cell) use ($invoice) {
                            $cell->setValue(config('constants.genderShort.'.$invoice->authorization->patient->gender));
                        });
                        $sheet->cell('L'.$counter, function($cell) use ($invoice) {
                            $cell->setValue($invoice->authorization->patient->state);
                        });
                        $sheet->cell('M'.$counter, function($cell) use ($invoice) {
                            $cell->setValue(sprintf("%03d", $invoice->authorization->patient->city));
                        });
                        $sheet->cell('N'.$counter, function($cell) use ($invoice) {
                            $cell->setValue($invoice->authorization->patient->zone);
                        });
                        array_push($arrPatients, $invoice->authorization->patient->id);
                        $counter++;
                        $counterUS++;
                    }
                }
            });

            $excel->sheet('AF', function($sheet) use ($invoices, &$counterAF) {
                $sheet->setHeight(1, 50);
                $sheet->getStyle('A1:Q1')->getAlignment()->setWrapText(true);
                $sheet->getStyle('A1:Q'.count($invoices))->getFont()->setSize(10);

                $sheet->setWidth('A', 11);
                $sheet->cell('A1', function($cell) {
                    $cell->setValue('Código del Prestador');
                    $cell->setFontColor('#0000FF');
                    $cell->setAlignment('center');
                });

                $sheet->setWidth('B', 45);
                $sheet->cell('B1', function($cell) {
                    $cell->setValue('Razón Social o Apellidos y Nombres del prestador');
                    $cell->setFontColor('#0000FF');
                    $cell->setAlignment('center');
                });

                $sheet->setWidth('C', 12);
                $sheet->cell('C1', function($cell) {
                    $cell->setValue('Tipo de Identificación');
                    $cell->setFontColor('#0000FF');
                    $cell->setAlignment('center');
                });
                $sheet->setWidth('D', 12);
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

                $sheet->setWidth('J', 15);
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

                $sheet->setWidth('Q', 14);
                $sheet->cell('Q1', function($cell) {
                    $cell->setValue('Valor Neto a Pagar por la entidad Contratante');
                    $cell->setFontColor('#0000FF');
                    $cell->setAlignment('center');
                });

                $counter = 2;
                foreach ($invoices as $invoice) {
                    $createdAt = \Carbon\Carbon::parse($invoice->created_at)->format("d/m/Y");
                    $total = $invoice->multiple ? array_sum(json_decode($invoice->multiple_totals, true)) : $invoice->total;

                    $sheet->cell('A'.$counter, function($cell) use ($invoice) {
                        $cell->setValue(sprintf("%12d", substr($invoice->company->doc, 0, 9)));
                    });
                    $sheet->cell('B'.$counter, function($cell) use ($invoice) {
                        $cell->setValue(mb_strtoupper($invoice->company->name));
                    });
                    $sheet->cell('C'.$counter, function($cell) use ($invoice) {
                        $cell->setValue($invoice->company->doc_type);
                    });
                    $sheet->cell('D'.$counter, function($cell) use ($invoice) {
                        $cell->setValue(substr($invoice->company->doc, 0, 9));
                    });
                    $sheet->cell('E'.$counter, function($cell) use ($invoice) {
                        $cell->setValue($invoice->number);
                    });
                    $sheet->cell('F'.$counter, function($cell) use ($createdAt) {
                        $cell->setValue($createdAt);
                    });
                    $sheet->cell('G'.$counter, function($cell) use ($createdAt) {
                        $cell->setValue($createdAt);
                    });
                    $sheet->cell('H'.$counter, function($cell) use ($createdAt) {
                        $cell->setValue($createdAt);
                    });
                    $sheet->cell('I'.$counter, function($cell) use ($invoice) {
                        $cell->setValue($invoice->eps->code);
                    });
                    $sheet->cell('J'.$counter, function($cell) use ($invoice) {
                        $cell->setValue(substr(mb_strtoupper($invoice->eps->name), 0, 30));
                    });
                    $sheet->cell('K'.$counter, function($cell) {
                        $cell->setValue('EPS');
                    });
                    $sheet->cell('N'.$counter, function($cell) {
                        $cell->setValue('0');
                    });
                    $sheet->cell('O'.$counter, function($cell) {
                        $cell->setValue('0');
                    });
                    $sheet->cell('P'.$counter, function($cell) {
                        $cell->setValue('0');
                    });
                    $sheet->cell('Q'.$counter, function($cell) use ($total) {
                        $cell->setValue(number_format($total, 2, ".", ""));
                    });
                    $counter++;
                    $counterAF++;
                }
            });

            $excel->sheet('AT', function($sheet) use ($invoices, &$counterAT) {
                $sheet->setHeight(1, 50);
                $sheet->getStyle('A1:K1')->getAlignment()->setWrapText(true);
                $sheet->getStyle('A1:K'.count($invoices))->getFont()->setSize(10);

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
                $sheet->setWidth('D', 12);
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

                $sheet->setWidth('J', 12);
                $sheet->cell('J1', function($cell) {
                    $cell->setValue('Valor unitario del material e insumo');
                    $cell->setFontColor('#0000FF');
                    $cell->setAlignment('center');
                });
                $sheet->setWidth('K', 12);
                $sheet->cell('K1', function($cell) {
                    $cell->setValue('Valor total del material e insumo');
                    $cell->setFontColor('#0000FF');
                    $cell->setAlignment('center');
                });

                $counter = 2;
                foreach ($invoices as $invoice) {
                    if ($invoice->multiple) {
                        foreach (json_decode($invoice->multiple_codes, true) as $key => $value) {
                            $currentAuthorization = Authorization::findByCode($value);
                            if ($currentAuthorization) {
                                $sheet->cell('A'.$counter, function($cell) use ($invoice) {
                                    $cell->setValue($invoice->number);
                                });
                                $sheet->cell('B'.$counter, function($cell) use ($invoice) {
                                    $cell->setValue(substr($invoice->company->doc, 0, 9));
                                });
                                $sheet->cell('C'.$counter, function($cell) use ($currentAuthorization) {
                                    $cell->setValue($currentAuthorization->patient->dni_type);
                                });
                                $sheet->cell('D'.$counter, function($cell) use ($currentAuthorization) {
                                    $cell->setValue($currentAuthorization->patient->dni);
                                });
                                $sheet->cell('E'.$counter, function($cell) use ($currentAuthorization) {
                                    $cell->setValue($currentAuthorization->code);
                                });
                                $sheet->cell('F'.$counter, function($cell) use ($invoice) {
                                    $cell->setValue("1");
                                });
                                $sheet->cell('G'.$counter, function($cell) use ($currentAuthorization) {
                                    $cell->setValue($currentAuthorization->service->code);
                                });
                                $sheet->cell('H'.$counter, function($cell) use ($currentAuthorization) {
                                    $cell->setValue(mb_strtoupper($currentAuthorization->service->name));
                                });
                                $sheet->cell('I'.$counter, function($cell) use ($invoice, $key) {
                                    $cell->setValue(json_decode($invoice->multiple_days, true)[$key]);
                                });
								$sheet->cell('J'.$counter, function($cell) use ($currentAuthorization) {
	                            	$cell->setValue(number_format($currentAuthorization->daily_price, 2, ".", ""));
		                        });
                                $sheet->cell('K'.$counter, function($cell) use ($invoice, $key) {
                                    $cell->setValue(number_format(floatval(json_decode($invoice->multiple_totals, true)[$key]), 2, ".", ""));
                                });
                                $counter++;
                                $counterAT++;
                            }
                        }
                    } else {
                        $sheet->cell('A'.$counter, function($cell) use ($invoice) {
                            $cell->setValue($invoice->number);
                        });
                        $sheet->cell('B'.$counter, function($cell) use ($invoice) {
                            $cell->setValue(substr($invoice->company->doc, 0, 9));
                        });
                        $sheet->cell('C'.$counter, function($cell) use ($invoice) {
                            $cell->setValue($invoice->authorization->patient->dni_type);
                        });
                        $sheet->cell('D'.$counter, function($cell) use ($invoice) {
                            $cell->setValue($invoice->authorization->patient->dni);
                        });
                        $sheet->cell('E'.$counter, function($cell) use ($invoice) {
                            $cell->setValue($invoice->authorization->code);
                        });
                        $sheet->cell('F'.$counter, function($cell) use ($invoice) {
                            $cell->setValue("1");
                        });
                        $sheet->cell('G'.$counter, function($cell) use ($invoice) {
                            $cell->setValue($invoice->authorization->service->code);
                        });
                        $sheet->cell('H'.$counter, function($cell) use ($invoice) {
                            $cell->setValue(mb_strtoupper($invoice->authorization->service->name));
                        });
                        $sheet->cell('I'.$counter, function($cell) use ($invoice) {
                            $cell->setValue($invoice->days);
                        });
                        $sheet->cell('J'.$counter, function($cell) use ($invoice) {
                            $cell->setValue($invoice->eps->daily_price);
                        });
                        $sheet->cell('K'.$counter, function($cell) use ($invoice) {
                            $cell->setValue(floatval($invoice->total));
                        });
                        $counter++;
                        $counterAT++;

                    }
                }
            });

            $excel->sheet('CT', function($sheet) use ($ripDate, $invoices, $id, $counterUS, $counterAT, $counterAF) {
                $sheet->setHeight(1, 50);
                $sheet->getStyle('A1:D1')->getAlignment()->setWrapText(true);
                $sheet->getStyle('A1:D5')->getFont()->setSize(10);

                $sheet->setWidth('A', 12);
                $sheet->cell('A1', function($cell) {
                    $cell->setValue('Código del prestador de servicios de salud');
                    $cell->setFontColor('#0000FF');
                    $cell->setAlignment('center');
                });

                $sheet->setWidth('B', 11);
                $sheet->cell('B1', function($cell) {
                    $cell->setValue('Fecha de remisión');
                    $cell->setFontColor('#0000FF');
                    $cell->setAlignment('center');
                });

                $sheet->setWidth('C', 11);
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

                $sheet->cell('A3', function($cell) use ($invoices) {
                    $cell->setValue(''.sprintf("%12d", substr($invoices[0]->company->doc, 0, 9)));
                });
                $sheet->cell('A4', function($cell) use ($invoices) {
                    $cell->setValue(''.sprintf("%12d", substr($invoices[0]->company->doc, 0, 9)));
                });
                $sheet->cell('A5', function($cell) use ($invoices) {
                    $cell->setValue(''.sprintf("%12d", substr($invoices[0]->company->doc, 0, 9)));
                });

                $createdAt = \Carbon\Carbon::parse($ripDate)->format("d/m/Y");
                $sheet->cell('B3', function($cell) use ($createdAt) {
                    $cell->setValue($createdAt);
                });
                $sheet->cell('B4', function($cell) use ($createdAt) {
                    $cell->setValue($createdAt);
                });
                $sheet->cell('B5', function($cell) use ($createdAt) {
                    $cell->setValue($createdAt);
                });

                $fileId = sprintf("%06d", $id);
                $sheet->cell('C3', function($cell) use ($fileId) {
                    $cell->setValue('AF'.$fileId);
                });
                $sheet->cell('C4', function($cell) use ($fileId) {
                    $cell->setValue('US'.$fileId);
                });
                $sheet->cell('C5', function($cell) use ($fileId) {
                    $cell->setValue('AT'.$fileId);
                });
                $sheet->cell('D3', function($cell) use ($counterAF) {
                    $cell->setValue($counterAF);
                });
                $sheet->cell('D4', function($cell) use ($counterUS) {
                    $cell->setValue($counterUS);
                });
                $sheet->cell('D5', function($cell) use ($counterAT) {
                    $cell->setValue($counterAT);
                });

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
            $excel->setActiveSheetIndex(0);
        })->store('xls', storage_path('app/'.config('constants.ripsFiles')));
    }

}
