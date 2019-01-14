<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

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
                $this->authorization->price->daily_price :
                $this->eps->price()->first()->daily_price;
        }

        if ($this->authorization->multiple) {
            return count($this->authorization->services) > 0 ?
                intval($this->authorization->services[0]->days) :
                intval($this->total / ($dailyPrice * (1 + count(explode(',', $this->authorization->multiple_services)))));
        }

        return intval($this->total / $dailyPrice);
    }

    public function getFormatNumberAttribute()
    {
        return $this->number;
    }

    public function getMultipleTotalsFormatedAttribute()
    {
        // $codes = json_decode($this->multiple_codes, true);
        // $totals = [];
        // foreach ($codes as $code) {
        //     $authorization = Authorization::findByCode($code);
        //     if ($authorization) {
        //         $totals[] = number_format($authorization->total_services, 2, ",", ".");
        //     }
        // }
        $totals = json_decode($this->multiple_totals, true);
        foreach ($totals as $key => $value) {
            $totals[$key] = number_format($value, 2, ",", ".");
        }

        return $totals;
    }

    /**
     * Methods
     */
    protected function storeRecord($request)
    {
        $invoice = new Invoice();

        $invoice->number = $request->get('number');
        $invoice->company_id = $request->get('company_id');
        $invoice->authorization_code = $request->get('authorization_code') ?: '';
        $invoice->total = $request->get('total') ?: 0;
        $invoice->notes = $request->get('notes');
        $invoice->created_at = $request->get('created_at');
        $invoice->eps_id = 0;
        $invoice->multiple = $request->get('multiple') == "1";
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
                    foreach($authorization->services as $as) {
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
        $invoice->save();
        Authorization::matchAuthorizationsWithInvoice($invoice->number);

        // InvoiceLog::storeRecord($request, config('constants.invoices.action.create'));

        if ($invoice->multiple) {
            $notes = "Factura para las autorizaciones ".join(",", $request->get('multiple_codes'))." de la EPS: ".$invoice->eps->code." - ".$invoice->eps->alias;

            $pucs = [];
            $pucTotal = 0;
            foreach ($request->get('multiple_totals') as $key => $total) {
                array_push($pucs, [
                    'code' => '270528'.sprintf("%02d", $invoice->eps_id),
                    'type' => 1,
                    'description' => 'Ingresos devengados por facturar otros para '.$invoice->eps->code .' - '.$invoice->eps->alias,
                    'amount' => $total,
                ]);
                array_push($pucs, [
                    'code' => '130505'.sprintf("%02d", $invoice->eps_id),
                    'type' => 0,
                    'description' => 'Cuentas por pagar para EPS '.$invoice->eps->code .' - '.$invoice->eps->alias,
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
            $notes = "Factura para autorización ".$invoice->authorization_code." de la EPS: ".$invoice->eps->code
                ." - ".$invoice->eps->alias;

            $pucs = [
                [
                    'code' => '270528'.sprintf("%02d", $invoice->eps_id),
                    'type' => 1,
                    'description' => 'Ingresos devengados por facturar otros para '.$invoice->eps->code .' - '.$invoice->eps->alias,
                    'amount' => $invoice->total,
                ],
                [
                    'code' => '130505'.sprintf("%02d", $invoice->eps_id),
                    'type' => 0,
                    'description' => 'Cuentas por pagar para EPS '.$invoice->eps->code .' - '.$invoice->eps->alias,
                    'amount' => $invoice->total,
                ],
            ];
            AccountingNote::storeRecord($invoice, $pucs, $notes, $invoice->total);
        }

        return $invoice;
    }

    protected function updateRecord($request)
    {
        $invoice = $this->find($request->get('id'));

        if ($invoice) {
            $oldAuthorizationCode = $invoice->authorization_code;
            $oldAuthorizationCodes = $invoice->multiple_codes;

            $invoice->number = $request->get('number');
            $invoice->company_id = $request->get('company_id');
            $invoice->authorization_code = $request->get('authorization_code') ?: '';
            $invoice->total = $request->get('total') ?: 0;
            $invoice->notes = $request->get('notes');
            $invoice->created_at = $request->get('created_at');
            $invoice->eps_id = 0;

            $invoice->multiple = $request->get('multiple') == "1";
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
                        foreach($authorization->services as $as) {
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

                $notes = "Factura para las autorizaciones ".join(",", $request->get('multiple_codes'))." de la EPS: ".$invoice->eps->code." - ".$invoice->eps->alias;

                $pucs = [];
                $pucTotal = 0;
                foreach ($request->get('multiple_totals') as $key => $total) {
                    array_push($pucs, [
                        'code' => '270528'.sprintf("%02d", $invoice->eps_id),
                        'type' => 1,
                        'description' => 'Ingresos devengados por facturar otros para '.$invoice->eps->code .' - '.$invoice->eps->alias,
                        'amount' => $total,
                    ]);
                    array_push($pucs, [
                        'code' => '130505'.sprintf("%02d", $invoice->eps_id),
                        'type' => 0,
                        'description' => 'Cuentas por pagar para EPS '.$invoice->eps->code .' - '.$invoice->eps->alias,
                        'amount' => $total,
                    ]);
                    $pucTotal += $total;
                    $currentAuthorization = Authorization::findByCode($request->get('multiple_codes')[$key]);
                    if ($currentAuthorization) {
                        $currentAuthorization->update(['invoice_id' => $invoice->id]);
                    }
                }
                AccountingNote::updateRecord($invoice, $pucs, $notes, $invoice->total);
            } else {
                $oldAuthorization = Authorization::findByCode($oldAuthorizationCode);
                if ($oldAuthorization) {
                    $oldAuthorization->update(['invoice_id' => 0]);
                    $invoice->save();
                }

                $authorization->update(['invoice_id' => $invoice->id]);
                $notes = "Factura para autorización ".$invoice->authorization_code." de la EPS: ".$invoice->eps->code
                    ." - ".$invoice->eps->alias;

                $pucs = [
                    [
                        'code' => '270528'.sprintf("%02d", $invoice->eps_id),
                        'type' => 1,
                        'description' => 'Ingresos devengados por facturar otros para '.$invoice->eps->code .' - '.$invoice->eps->alias,
                        'amount' => $invoice->total,
                    ],
                    [
                        'code' => '130505'.sprintf("%02d", $invoice->eps_id),
                        'type' => 0,
                        'description' => 'Cuentas por pagar para EPS '.$invoice->eps->code .' - '.$invoice->eps->alias,
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
                ->whereBetween('created_at', [$initialDate, $finalDate])
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
          echo "\n\nInvoice id: ".$invoice->id." Invoice number: ".$invoice->number;
          if ($invoice->multiple) {
              foreach (json_decode($invoice->multiple_codes, true) as $code) {
                  $currentAuthorization = Authorization::findByCode($code);
                  echo "\n-> Multiple, processing code: ".$code;
                  if ($currentAuthorization and $currentAuthorization->invoice_id == 0) {
                      echo "\n[ <<< TRUE >>> ]";
                      $currentAuthorization->update(['invoice_id' => $invoice->id]);
                  }
              }
          } else {
              echo "\n-> Single, processing code: ".$invoice->authorization_code;
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
            'multiple_codes' => '["'.$invoice->authorization_code.'"]',
            'multiple_totals' => '["'.$invoice->total.'"]',
            'multiple_days' => '["'.$invoice->days.'"]',
          ]);
          echo "\nProcessing invoice number: ".$invoice->number;
          if ($key > $max) {
            break;
          }
      }

      return "Invoices processed: ".count($invoices);
    }

    protected function searchRecords($search)
    {
        return $this::where('number', 'like', '%'.$search.'%')
            ->orWhere('authorization_code', 'like', '%'.$search.'%')
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
            foreach (json_decode($invoice->multiple_codes,true) as $k => $code) {
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
                    'days' => json_decode($invoice->multiple_days,true)[$k]
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
}
