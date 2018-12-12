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
    public function getDaysAttribute()
    {
        if ($this->authorization->multiple) {
            return intval($this->total / ($this->eps->daily_price * (1 + count(explode(',', $this->authorization->multiple_services)))));
        }

        return intval($this->total / $this->eps->daily_price);
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
        }

        $authorization = Authorization::findByCode($request->get('authorization_code') ?: $request->get('multiple_codes')[0]);
        if ($authorization) {
            $invoice->eps_id = $authorization->eps_id;
        }
        $invoice->save();

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

    protected function setInvoiceToAuthorizations()
    {
        $invoices = $this::all();

        foreach ($invoices as $key => $invoice) {
            echo "\n\nInvoice id :".$invoice ;
            if ($invoice->multiple) {
                foreach (json_decode($invoice->multiple_codes, true) as $code) {
                    $currentAuthorization = Authorization::findByCode($code);
                    echo "\nMultiple, processing code: ".$code;
                    if ($currentAuthorization and $currentAuthorization->invoice_id == 0) {
                        echo "\n[ <<< TRUE >>> ]";
                        $currentAuthorization->update(['invoice_id' => $invoice->id]);
                    }
                }
            } else {
                echo "\nSingle, processing code: ".$invoice->authorization_code;
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
}
