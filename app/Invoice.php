<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Maatwebsite\Excel\Facades\Excel;

class Invoice extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'number',
        'company_id',
        'authorization_code',
        'eps_id',
        //        'patient_id',
        'total',
        'payment',
        'status',
        'notes',
        'created_at',
        'multiple',
        'multiple_codes',
        'multiple_days',
        'multiple_totals',
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
    public function authorization()
    {
        return $this->hasOne(Authorization::class, 'code', 'authorization_code');
    }

    public function eps()
    {
        return $this->hasOne(Eps::class, 'id', 'eps_id');
    }

    public function company()
    {
        return $this->hasOne(Company::class, 'id', 'company_id');
    }

    /**
     * Attributes
     */
    public function DaysAttribute()
    {
        $dailyPrice = $this->eps->daily_price;
        if ($dailyPrice == 0) {
            $dailyPrice = $this->authorization->price ?
                $this->authorization->price->daily_price : $this->eps->price()->first()->daily_price;
        }

        if ($this->authorization->multiple) {
            return count($this->authorization->services) > 0 ?
                intval($this->authorization->services[0]->days) : intval($this->total / ($dailyPrice * (1 + count(explode(',', $this->authorization->multiple_services)))));
        }

        return intval($this->total / $dailyPrice);
    }

    public function getFormatNumberAttribute()
    {
        return $this->number;
    }

    public function getMultipleTotalsFormatedAttribute()
    {
        $totals = json_decode($this->multiple_totals, true);
        foreach ($totals as $key => $value) {
            $totals[$key] = number_format($value, 2, ",", ".");
        }

        return $totals;
    }

    /**
     * Scopes
     */

    public function scopeForSelect($query, $id = 'id', $name = 'name')
    {
        return $query->select([$id, $name])
            ->pluck($name, $id);
    }

    public function scopeSearch($query, $epsId, $companyId, $request)
    {
        $initialNumber = $request->get('initial_number');
        $finalNumber   = $request->get('final_number');
        $initialDate   = $request->get('initial_date') ? $request->get('initial_date') . ' 00:00:00' : null;
        $finalDate     = $request->get('final_date') ? $request->get('final_date') . ' 23:59:59' : null;

        $query->where('eps_id', $epsId)
            ->where('company_id', $companyId);

        if ($initialNumber and $finalNumber) {
            $query->whereBetween('number', [$initialNumber, $finalNumber]);
        }

        if ($initialDate and $finalDate) {
            $query->whereBetween('created_at', [$initialDate, $finalDate]);
        }

        return $query;
    }

    /**
     * Methods
     */

    public function getPatient()
    {
        $codesArray = json_decode($this->multiple_codes, true);
        if (count($codesArray) > 0) {
            $query = Authorization::with('patient')
                ->where('code', $codesArray[0])
                ->first();

            return optional($query)->patient;
        }
        return null;
    }

    public function calculateTotal()
    {
        return array_sum(json_decode($this->multiple_totals, true));
    }

    protected function storeRecord($request)
    {
        $invoice = new Invoice();

        $request->request->add([
            'created_at' => $request->get('created_at') . ' ' . \Carbon\Carbon::now()->format('H:i:s')
        ]);

        $invoice->number = $request->get('number');
        $invoice->company_id = $request->get('company_id');
        $invoice->authorization_code = $request->get('authorization_code') ?: '';
        $invoice->total = $request->get('total') ?: 0;
        $invoice->notes = $request->get('notes');
        $invoice->created_at = $request->get('created_at');
        $invoice->eps_id = 0;
        $invoice->multiple = 1; //$request->get('multiple') == "1";

        if ($invoice->multiple) {
            $invoice->multiple_codes = json_encode($request->get('multiple_codes'));
            $invoice->multiple_days = json_encode($request->get('multiple_days'));
            $invoice->multiple_totals = json_encode($request->get('multiple_totals'));

            $invoiceCodes = json_decode($invoice->multiple_codes, true);
            $invoiceDays = json_decode($invoice->multiple_days, true);
            $invoiceTotals = json_decode($invoice->multiple_totals, true);

            foreach ($invoiceCodes as $k => $val) {
                $authorization = Authorization::findByCode($val);
                if ($authorization) {
                    $tot = 0;
                    foreach ($authorization->services as $keyAs => $as) {
                        // if ($keyAs == 0) {
                        //     $tot += $as->price * $invoiceDays[$keyAs];    
                        // } else {
                        $tot += $as->price * $as->days;
                        // }
                    }

                    $invoiceTotals[$k] = $tot;
                }
            }

            $invoice->multiple_codes = json_encode($invoiceCodes);
            $invoice->multiple_days = json_encode($invoiceDays);
            $invoice->multiple_totals = json_encode($invoiceTotals);
        }

        $authorization = Authorization::findByCode($request->get('authorization_code') ?: $request->get('multiple_codes')[0]);
        if ($authorization) {
            $invoice->eps_id = $authorization->eps_id;
        }
        $invoice->save();
        Authorization::matchAuthorizationsWithInvoice($invoice->number);

        // InvoiceLog::storeRecord($request, config('constants.invoices.action.create'));

        if ($invoice->multiple) {

            if (is_array($request->get('multiple_codes')) and count($request->get('multiple_codes')) == 1) {
                $notes = "Factura para la autorización " . join(",", $request->get('multiple_codes')) . " de la EPS: " . $invoice->eps->code . " - " . $invoice->eps->alias;
            } else {
                $notes = "Factura para la(s) autorizacion(es) " . join(",", $request->get('multiple_codes')) . " de la EPS: " . $invoice->eps->code . " - " . $invoice->eps->alias;
            }

            $pucs = [];
            $pucTotal = 0;
            foreach ($request->get('multiple_totals') as $key => $total) {
                array_push($pucs, [
                    'code' => '270528' . sprintf("%02d", $invoice->eps_id),
                    'type' => 1,
                    'description' => 'Ingresos devengados por facturar otros para ' . $invoice->eps->code . ' - ' . $invoice->eps->alias,
                    'amount' => $total,
                ]);
                array_push($pucs, [
                    'code' => '130505' . sprintf("%02d", $invoice->eps_id),
                    'type' => 0,
                    'description' => 'Cuentas por pagar para EPS ' . $invoice->eps->code . ' - ' . $invoice->eps->alias,
                    'amount' => $total,
                ]);
                $pucTotal += $total;
                $currentAuthorization = Authorization::findByCode($request->get('multiple_codes')[$key]);
                if ($currentAuthorization) {
                    $currentAuthorization->update(['invoice_id' => $invoice->id]);
                }
            }
            AccountingNote::storeRecord($invoice, $pucs, $notes, $pucTotal);
        } else {
            $authorization->update(['invoice_id' => $invoice->id]);
            $notes = "Factura para autorización " . $invoice->authorization_code . " de la EPS: " . $invoice->eps->code
                . " - " . $invoice->eps->alias;

            $pucs = [
                [
                    'code' => '270528' . sprintf("%02d", $invoice->eps_id),
                    'type' => 1,
                    'description' => 'Ingresos devengados por facturar otros para ' . $invoice->eps->code . ' - ' . $invoice->eps->alias,
                    'amount' => $invoice->total,
                ],
                [
                    'code' => '130505' . sprintf("%02d", $invoice->eps_id),
                    'type' => 0,
                    'description' => 'Cuentas por pagar para EPS ' . $invoice->eps->code . ' - ' . $invoice->eps->alias,
                    'amount' => $invoice->total,
                ],
            ];
            AccountingNote::storeRecord($invoice, $pucs, $notes, $invoice->total);
        }

        return $invoice;
    }

    protected function updateRecord($request, $id)
    {
        $invoice = $this->find($id);

        if ($invoice) {
            $request->request->add([
                'created_at' => $request->get('created_at') . ' ' . \Carbon\Carbon::now()->format('H:i:s')
            ]);

            $oldAuthorizationCode = $invoice->authorization_code;
            $oldAuthorizationCodes = $invoice->multiple_codes;

            $invoice->number = $request->get('number');
            $invoice->company_id = $request->get('company_id');
            $invoice->authorization_code = $request->get('authorization_code') ?: '';
            $invoice->total = $request->get('total') ?: 0;
            $invoice->notes = $request->get('notes');
            $invoice->created_at = $request->get('created_at');
            $invoice->eps_id = 0;

            $invoice->multiple = 1;
            if ($invoice->multiple) {
                $invoice->multiple_codes = json_encode($request->get('multiple_codes'));
                $invoice->multiple_days = json_encode($request->get('multiple_days'));
                $invoice->multiple_totals = json_encode($request->get('multiple_totals'));

                $invoiceCodes = json_decode($invoice->multiple_codes, true);
                $invoiceDays = json_decode($invoice->multiple_days, true);
                $invoiceTotals = json_decode($invoice->multiple_totals, true);

                foreach ($invoiceCodes as $k => $val) {
                    $authorization = Authorization::findByCode($val);
                    if ($authorization) {
                        $tot = 0;
                        foreach ($authorization->services as $as) {
                            $tot += $as->price * $as->days;
                        }

                        $invoiceTotals[$k] = $tot;
                    }
                }

                $invoice->multiple_codes = json_encode($invoiceCodes);
                $invoice->multiple_days = json_encode($invoiceDays);
                $invoice->multiple_totals = json_encode($invoiceTotals);
            }

            $authorization = Authorization::findByCode($request->get('authorization_code') ?: $request->get('multiple_codes')[0]);
            if ($authorization) {
                $invoice->eps_id = $authorization->eps_id;
            }

            if ($invoice->multiple and $oldAuthorizationCodes) {
                foreach (json_decode($oldAuthorizationCodes, true) as $oldCode) {
                    $oldAuthorization = Authorization::findByCode($oldCode);
                    if ($oldAuthorization) {
                        $oldAuthorization->update(['invoice_id' => 0]);
                    }
                }
                $invoice->save();

                $notes = "Factura para las autorizaciones " . join(",", $request->get('multiple_codes')) . " de la EPS: " . $invoice->eps->code . " - " . $invoice->eps->alias;

                $pucs = [];
                $pucTotal = 0;
                foreach ($request->get('multiple_totals') as $key => $total) {
                    array_push($pucs, [
                        'code' => '270528' . sprintf("%02d", $invoice->eps_id),
                        'type' => 1,
                        'description' => 'Ingresos devengados por facturar otros para ' . $invoice->eps->code . ' - ' . $invoice->eps->alias,
                        'amount' => $total,
                    ]);
                    array_push($pucs, [
                        'code' => '130505' . sprintf("%02d", $invoice->eps_id),
                        'type' => 0,
                        'description' => 'Cuentas por pagar para EPS ' . $invoice->eps->code . ' - ' . $invoice->eps->alias,
                        'amount' => $total,
                    ]);
                    $pucTotal += $total;
                    $currentAuthorization = Authorization::findByCode($request->get('multiple_codes')[$key]);
                    if ($currentAuthorization) {
                        $currentAuthorization->update(['invoice_id' => $invoice->id]);
                    }
                }
                AccountingNote::updateRecord($invoice, $pucs, $notes, $pucTotal);
            } else {
                $oldAuthorization = Authorization::findByCode($oldAuthorizationCode);
                if ($oldAuthorization) {
                    $oldAuthorization->update(['invoice_id' => 0]);
                    $invoice->save();
                }

                $authorization->update(['invoice_id' => $invoice->id]);
                $notes = "Factura para autorización " . $invoice->authorization_code . " de la EPS: " . $invoice->eps->code
                    . " - " . $invoice->eps->alias;

                $pucs = [
                    [
                        'code' => '270528' . sprintf("%02d", $invoice->eps_id),
                        'type' => 1,
                        'description' => 'Ingresos devengados por facturar otros para ' . $invoice->eps->code . ' - ' . $invoice->eps->alias,
                        'amount' => $invoice->total,
                    ],
                    [
                        'code' => '130505' . sprintf("%02d", $invoice->eps_id),
                        'type' => 0,
                        'description' => 'Cuentas por pagar para EPS ' . $invoice->eps->code . ' - ' . $invoice->eps->alias,
                        'amount' => $invoice->total,
                    ],
                ];
                AccountingNote::updateRecord($invoice, $pucs, $notes, $invoice->total);
            }

            Authorization::matchAuthorizationsWithInvoice($invoice->number);
        }

        return $invoice;
    }

    protected function getUnpaidInvoices($epsId)
    {
        return $this->where('eps_id', $epsId)
            ->where('status', 0)
            ->get();
    }

    protected function getPaidInvoices($epsId)
    {
        return $this->where('eps_id', $epsId)
            ->where('status', '<>', 0)
            ->get();
    }

    protected function getInvoicesByEpsId($epsId, $initialDate = null, $finalDate = null)
    {
        if ($initialDate and $finalDate) {
            return $this->where('eps_id', $epsId)
                ->whereBetween('created_at', [
                    substr($initialDate, 0, 10) . ' 00:00:00',
                    substr($finalDate, 0, 10) . ' 23:59:59',
                ])
                ->get();
        }

        return $this->where('eps_id', $epsId)
            ->get();
    }

    protected function getInvoicesByEpsIdNumber($epsId, $initialNumber = null, $finalNumber = null)
    {
        if ($initialNumber and $finalNumber) {
            return $this->where('eps_id', $epsId)
                ->whereBetween('number', [$initialNumber, $finalNumber])
                ->orderBy('number')
                ->get();
        }

        return $this->where('eps_id', $epsId)
            ->get();
    }

    protected function getInvoiceByNumber($number)
    {
        return $this->where('number', $number)
            ->first();
    }

    protected function getInvoiceByAuthorizationCode($code)
    {
        return $this->where('authorization_code', $code)
            ->first();
    }

    protected function setInvoiceToAuthorizations($max = 7650)
    {
        $invoices = $this::all();

        foreach ($invoices as $key => $invoice) {
            if ($invoice->number > $max) {
                break;
            }
            echo "\n\nInvoice id: " . $invoice->id . " Invoice number: " . $invoice->number;
            if ($invoice->multiple) {
                foreach (json_decode($invoice->multiple_codes, true) as $code) {
                    $currentAuthorization = Authorization::findByCode($code);
                    echo "\n-> Multiple, processing code: " . $code;
                    if ($currentAuthorization and $currentAuthorization->invoice_id == 0) {
                        echo "\n[ <<< TRUE >>> ]";
                        $currentAuthorization->update(['invoice_id' => $invoice->id]);
                    }
                }
            } else {
                echo "\n-> Single, processing code: " . $invoice->authorization_code;
                $currentAuthorization = Authorization::findByCode($invoice->authorization_code);
                if ($currentAuthorization and $currentAuthorization->invoice_id == 0) {
                    echo "\n[ <<< TRUE >>> ]";
                    $currentAuthorization->update(['invoice_id' => $invoice->id]);
                }
            }
        }
    }

    protected function getLastNumber()
    {
        $result = $this::withTrashed()
            ->get()
            ->last();

        return $result ? $result->number : 0;
    }

    protected function convertToMultiple($max = 30)
    {
        $invoices = $this::where('multiple', 0)
            ->get();

        if (!$invoices) {
            return "No single invoices were found";
        }

        foreach ($invoices as $key => $invoice) {
            $invoice->update([
                'multiple' => config('constants.status.active'),
                'multiple_codes' => '["' . $invoice->authorization_code . '"]',
                'multiple_totals' => '["' . $invoice->total . '"]',
                'multiple_days' => '["' . $invoice->days . '"]',
            ]);
            echo "\nProcessing invoice number: " . $invoice->number;
            if ($key > $max) {
                break;
            }
        }

        return "Invoices processed: " . count($invoices);
    }

    protected function searchRecords($search)
    {
        return $this::where('number', 'like', '%' . $search . '%')
            ->orWhere('authorization_code', 'like', '%' . $search . '%')
            ->orderBy('number', 'desc')
            ->paginate(config('constants.pagination'));
    }

    protected function fixSimpleInvoice($number)
    {
        $invoice = $this->getInvoiceByNumber($number);
        if (!$invoice) {
            echo "\nInvoice $number not found";
            return;
        }

        if ($invoice->multiple) {
            foreach (json_decode($invoice->multiple_codes, true) as $k => $code) {
                $authorization = Authorization::findByCode($code);
                if (!$authorization) {
                    echo "\nAuthorization $code not found";
                    continue;
                }
                $authorizationService = AuthorizationService::checkIfExists($authorization);
                if (!$authorizationService) {
                    echo "\nAuthorizationService not found";
                    continue;
                }
                $authorizationService->update([
                    'days' => json_decode($invoice->multiple_days, true)[$k]
                ]);
                echo "\nDone with authorization $code";
            }
        } else {
            $authorization = Authorization::findByCode($invoice->authorization_code);
            if (!$authorization) {
                echo "\nAuthorization $invoice->authorization_code not found";
                return;
            }
            $authorizationService = AuthorizationService::checkIfExists($authorization);
            if (!$authorizationService) {
                echo "\nAuthorizationService not found";
                return;
            }
            $authorizationService->update([
                'days' => $invoice->total / $authorizationService->price
            ]);
        }
        echo "\nSuccess!! On invoice number $number";
    }

    protected function export($request, $query)
    {
        $method     = $request->get('export');
        $exportDate = $request->get('export_date');
        $eps        = Eps::findOrFail($request->get('eps_id'));
        $company    = Company::findOrFail($request->get('company_id'));
        $invoices   = $query->orderBy('number', 'asc')
            ->get();

        if ($method == config('constants.exportMethods.relation')) {
            return $this->pdfRelation($company, $eps, $invoices, $exportDate);
        } elseif ($method == config('constants.exportMethods.excel')) {
            return $this->excel($eps, $invoices, $exportDate);
        } elseif ($method == config('constants.exportMethods.worldOffice')) {
            return $this->excelWorldOffice($eps, $company, $invoices, $exportDate);
        }

        return $this->pdfVolume($company, $eps, $invoices, $exportDate);
    }

    protected function pdfVolume($company, $eps, $invoices, $createdAt)
    {
        ini_set("pcre.backtrack_limit", "5000000");
        $html = \View::make('invoice.pdf_volume', compact('invoices', 'company', 'eps', 'createdAt'));
        $mpdf = new \Mpdf\Mpdf([
            'margin_left' => 20,
            'margin_right' => 15,
            'margin_top' => 48,
            'margin_bottom' => 25,
            'margin_header' => 10,
            'margin_footer' => 10
        ]);
        $mpdf->SetProtection(array('print'));
        $mpdf->SetTitle($company->name . " - Volumen de Facturas " . $eps->alias);
        $mpdf->SetAuthor($company->name);
        $mpdf->SetDisplayMode('fullpage');
        $mpdf->WriteHTML($html);

        return $mpdf->Output($eps->alias . ' - Volumen de Facturas.pdf', 'I');
    }

    protected function pdfRelation($company, $eps, $invoices, $createdAt)
    {
        $initialDate = $invoices->first()->created_at;
        $finalDate   = $invoices->last()->created_at;

        ini_set("pcre.backtrack_limit", "5000000");
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
        $mpdf->SetTitle($company->name . " - Relación de Facturas " . $eps->alias);
        $mpdf->SetAuthor($company->name);
        $mpdf->SetDisplayMode('fullpage');
        $mpdf->WriteHTML($html);

        return $mpdf->Output($eps->alias . ' - Relación de Facturas.pdf', 'I');
    }

    protected function excel($eps, $invoices, $exportDate)
    {
        $fileName  = $eps->alias . ' - Facturas';
        $createdAt = Carbon::parse($exportDate)->format('d/m/Y');
        set_time_limit(0);
        return Excel::create($fileName, function ($excel) use ($eps, $invoices, $createdAt) {
            $excel->getDefaultStyle()->getFont()->setSize(10);
            $excel->sheet('Hoja1', function ($sheet) use ($eps, $invoices, $createdAt) {
                $sheet->setHeight(1, 20);
                $sheet->getStyle('A1:E1')->getAlignment()->setWrapText(true);

                $sheet->setWidth('A', 40);
                $sheet->cell('A1', function ($cell) {
                    $cell->setValue('EPS');
                    $cell->setFontColor('#0000FF');
                    $cell->setAlignment('center');
                });

                $sheet->setWidth('B', 13);
                $sheet->cell('B1', function ($cell) {
                    $cell->setValue('NIT');
                    $cell->setFontColor('#0000FF');
                    $cell->setAlignment('center');
                });

                $sheet->setWidth('C', 13);
                $sheet->cell('C1', function ($cell) {
                    $cell->setValue('# Factura');
                    $cell->setFontColor('#0000FF');
                    $cell->setAlignment('center');
                });

                $sheet->setWidth('D', 13);
                $sheet->cell('D1', function ($cell) {
                    $cell->setValue('Monto');
                    $cell->setFontColor('#0000FF');
                    $cell->setAlignment('center');
                });

                $sheet->setWidth('E', 12);
                $sheet->cell('E1', function ($cell) {
                    $cell->setValue('Fecha');
                    $cell->setFontColor('#0000FF');
                    $cell->setAlignment('center');
                });

                $counter = 2;
                foreach ($invoices as $invoice) {
                    $sheet->cell('A' . $counter, function ($cell) use ($eps) {
                        $cell->setValue($eps->name);
                    });
                    $sheet->cell('B' . $counter, function ($cell) use ($eps) {
                        $cell->setValue($eps->nit);
                    });
                    $sheet->cell('C' . $counter, function ($cell) use ($invoice) {
                        $cell->setValue($invoice->number . '');
                    });
                    $sheet->cell('D' . $counter, function ($cell) use ($invoice) {
                        // $cell->setValue(number_format($invoice->calculateTotal(), 2, ',', '.'));
                        $cell->setValue($invoice->calculateTotal() . '');
                    });
                    $sheet->cell('E' . $counter, function ($cell) use ($createdAt) {
                        $cell->setValue($createdAt);
                    });
                    $counter++;
                }
            });
        })->export('xls');
    }

    protected function excelWorldOffice($eps, $company, $invoices, $exportDate)
    {
        $fileName  = 'FacturasWO - '. $eps->alias;
        $createdAt = Carbon::parse($exportDate)->format('d/m/Y');
        $expiresAt = Carbon::parse($exportDate)->addMonth()->format('d/m/Y');
        set_time_limit(0);
        return Excel::create($fileName, function ($excel) use ($eps, $company, $invoices, $createdAt, $expiresAt) {
            $excel->getDefaultStyle()->getFont()->setSize(10);
            $excel->sheet('Hoja1', function ($sheet) use ($eps, $company, $invoices, $createdAt, $expiresAt) {
                $sheet->setHeight(1, 40);
                $sheet->getStyle('A1:AM1')->getAlignment()->setWrapText(true);

                $sheet->setWidth('A', 45);
                $sheet->cell('A1', function ($cell) {
                    $cell->setValue('Encab: Empresa');
                    $cell->setFontColor('#0000FF');
                    $cell->setAlignment('left');
                });

                $sheet->setWidth('B', 13);
                $sheet->cell('B1', function ($cell) {
                    $cell->setValue('Encab: Tipo Documento');
                    $cell->setFontColor('#0000FF');
                    $cell->setAlignment('left');
                });

                $sheet->setWidth('C', 13);
                $sheet->cell('C1', function ($cell) {
                    $cell->setValue('Encab: Prefijo');
                    $cell->setFontColor('#0000FF');
                    $cell->setAlignment('left');
                });

                $sheet->setWidth('D', 13);
                $sheet->cell('D1', function ($cell) {
                    $cell->setValue('Encab: Documento Número');
                    $cell->setFontColor('#0000FF');
                    $cell->setAlignment('left');
                });

                $sheet->setWidth('E', 12);
                $sheet->cell('E1', function ($cell) {
                    $cell->setValue('Encab: Fecha');
                    $cell->setFontColor('#0000FF');
                    $cell->setAlignment('left');
                });

                $sheet->setWidth('F', 12);
                $sheet->cell('F1', function ($cell) {
                    $cell->setValue('Encab: Tercero Interno');
                    $cell->setFontColor('#0000FF');
                    $cell->setAlignment('left');
                });

                $sheet->setWidth('G', 12);
                $sheet->cell('G1', function ($cell) {
                    $cell->setValue('Encab: Tercero Externo');
                    $cell->setFontColor('#0000FF');
                    $cell->setAlignment('left');
                });

                $sheet->setWidth('H', 15);
                $sheet->cell('H1', function ($cell) {
                    $cell->setValue('Encab: Nota');
                    $cell->setFontColor('#0000FF');
                    $cell->setAlignment('left');
                });

                $sheet->setWidth('I', 12);
                $sheet->cell('I1', function ($cell) {
                    $cell->setValue('Encab: FormaPago');
                    $cell->setFontColor('#0000FF');
                    $cell->setAlignment('left');
                });

                $sheet->setWidth('J', 12);
                $sheet->cell('J1', function ($cell) {
                    $cell->setValue('Encab: Fecha Entrega');
                    $cell->setFontColor('#0000FF');
                    $cell->setAlignment('left');
                });

                $sheet->setWidth('K', 12);
                $sheet->cell('K1', function ($cell) {
                    $cell->setValue('Encab: Verificado');
                    $cell->setFontColor('#0000FF');
                    $cell->setAlignment('left');
                });

                $sheet->setWidth('L', 12);
                $sheet->cell('L1', function ($cell) {
                    $cell->setValue('Encab: Anulado');
                    $cell->setFontColor('#0000FF');
                    $cell->setAlignment('left');
                });

                $sheet->setWidth('M', 12);
                $sheet->cell('M1', function ($cell) {
                    $cell->setValue('Nombre de paciente');
                    $cell->setFontColor('#0000FF');
                    $cell->setAlignment('left');
                });
                
                $sheet->setWidth('N', 12);
                $sheet->cell('N1', function ($cell) {
                    $cell->setValue('Tipo de documento');
                    $cell->setFontColor('#0000FF');
                    $cell->setAlignment('left');
                });
                
                $sheet->setWidth('O', 30);
                $sheet->cell('O1', function ($cell) {
                    $cell->setValue('Numero de documento');
                    $cell->setFontColor('#0000FF');
                    $cell->setAlignment('left');
                });

                $sheet->setWidth('P', 12);
                $sheet->cell('P1', function ($cell) {
                    $cell->setValue('Detalle: Producto');
                    $cell->setFontColor('#0000FF');
                    $cell->setAlignment('left');
                });

                $sheet->setWidth('Q', 12);
                $sheet->cell('Q1', function ($cell) {
                    $cell->setValue('Detalle: Bodega');
                    $cell->setFontColor('#0000FF');
                    $cell->setAlignment('left');
                });

                $sheet->setWidth('R', 12);
                $sheet->cell('R1', function ($cell) {
                    $cell->setValue('Detalle: UnidadDeMedida');
                    $cell->setFontColor('#0000FF');
                    $cell->setAlignment('left');
                });

                $sheet->setWidth('S', 12);
                $sheet->cell('S1', function ($cell) {
                    $cell->setValue('EDetalle: Cantidad');
                    $cell->setFontColor('#0000FF');
                    $cell->setAlignment('left');
                });

                $sheet->setWidth('T', 12);
                $sheet->cell('T1', function ($cell) {
                    $cell->setValue('Detalle: IVA');
                    $cell->setFontColor('#0000FF');
                    $cell->setAlignment('left');
                });

                $sheet->setWidth('U', 12);
                $sheet->cell('U1', function ($cell) {
                    $cell->setValue('Detalle: Valor Unitario');
                    $cell->setFontColor('#0000FF');
                    $cell->setAlignment('left');
                });

                $sheet->setWidth('V', 12);
                $sheet->cell('V1', function ($cell) {
                    $cell->setValue('Detalle: Descuento');
                    $cell->setFontColor('#0000FF');
                    $cell->setAlignment('left');
                });

                $sheet->setWidth('W', 12);
                $sheet->cell('W1', function ($cell) {
                    $cell->setValue('Detalle: Vencimiento');
                    $cell->setFontColor('#0000FF');
                    $cell->setAlignment('left');
                });

                $counter = 2;
                foreach ($invoices as $invoice) {
                    if (!$invoice->multiple) {
                        continue;
                    }
                    foreach (json_decode($invoice->multiple_codes, true) as $value) {
                        $currentAuthorization = Authorization::findByCode($value);
                        if (!$currentAuthorization) {
                            continue;
                        }

                        $patient = $currentAuthorization->patient;
                        foreach ($currentAuthorization->services as $service) {
                            $sheet->cell('A' . $counter, function ($cell) use ($company) {
                                $cell->setValue(str_replace(' ', '  ', Utilities::normalizeString(mb_strtoupper($company->name))));
                            });
                            $sheet->cell('B' . $counter, function ($cell) use ($eps) {
                                $cell->setValue('FV');
                            });
                            $sheet->cell('C' . $counter, function ($cell) {
                                $cell->setValue(config('constants.companyInfo.invoicePreffix'));
                            });
                            $sheet->cell('D' . $counter, function ($cell) use ($invoice) {
                                $cell->setValue($invoice->number . '');
                            });
                            $sheet->cell('E' . $counter, function ($cell) use ($createdAt) {
                                $cell->setValue($createdAt);
                            });
                            $sheet->cell('F' . $counter, function ($cell) {
                                $cell->setValue('32657607'); // Cédula de Ingrid
                            });
                            $sheet->cell('G' . $counter, function ($cell) use ($eps) {
                                $cell->setValue(explode('-', $eps->nit)[0]);
                            });
                            $sheet->cell('H' . $counter, function ($cell) {
                                $cell->setValue('Factura de Venta');
                            });
                            $sheet->cell('I' . $counter, function ($cell) {
                                $cell->setValue('Credito');
                            });
                            $sheet->cell('J' . $counter, function ($cell) use ($createdAt) {
                                $cell->setValue($createdAt);
                            });
                            $sheet->cell('K' . $counter, function ($cell) {
                                $cell->setValue('-1');
                            });
                            $sheet->cell('L' . $counter, function ($cell) {
                                $cell->setValue('0');
                            });
                            $sheet->cell('M' . $counter, function ($cell) use ($patient) {
                                $cell->setValue($patient->full_name);
                            });
                            $sheet->cell('N' . $counter, function ($cell) use ($patient) {
                                $cell->setValue($patient->dni_type);
                            });
                            $sheet->cell('O' . $counter, function ($cell) use ($patient) {
                                $cell->setValue($patient->dni);
                            });
                            $sheet->cell('P' . $counter, function ($cell) use ($service) {
                                $cell->setValue($service->service->code);
                            });
                            $sheet->cell('Q' . $counter, function ($cell) {
                                $cell->setValue('Principal');
                            });
                            $sheet->cell('R' . $counter, function ($cell) {
                                $cell->setValue('Und.');
                            });
                            $sheet->cell('S' . $counter, function ($cell) use ($service) {
                                $cell->setValue($service->days . '');
                            });
                            $sheet->cell('T' . $counter, function ($cell) {
                                $cell->setValue('0');
                            });
                            $sheet->cell('U' . $counter, function ($cell) use ($service) {
                                $cell->setValue(($service->price) . '');
                            });
                            $sheet->cell('V' . $counter, function ($cell) {
                                $cell->setValue('0');
                            });
                            $sheet->cell('W' . $counter, function ($cell) use ($expiresAt) {
                                $cell->setValue($expiresAt);
                            });
                            $counter++;
                        }
                    }
                }
            });
        })->export('xls');
    }
}
