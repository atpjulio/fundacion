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
        'status',
        'notes',
        'created_at',
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

    /**
     * Methods
     */
    protected function storeRecord($request)
    {
        $invoice = new Invoice();

        $invoice->number = $request->get('number');
        $invoice->company_id = $request->get('company_id');
        $invoice->authorization_code = $request->get('authorization_code');
        $invoice->total = $request->get('total');
        $invoice->notes = $request->get('notes');
        $invoice->created_at = $request->get('created_at');
        $invoice->eps_id = 0;

        $authorization = Authorization::findByCode($request->get('authorization_code'));
        if ($authorization) {
            $invoice->eps_id = $authorization->eps_id;
        }

        $invoice->save();

        InvoiceLog::storeRecord($request, config('constants.invoices.action.create'));

        $notes = "Factura para autorización ".$invoice->authorization_code." de la EPS: ".$invoice->eps->code
            ." - ".$invoice->eps->alias;

        $pucs = [
            [
                'code' => '414010'.$invoice->eps_id,
                'type' => 1,
                'description' => 'Campamento y otros tipos de hospedaje para EPS '.$invoice->eps->code .' - '.$invoice->eps->alias,
                'amount' => $invoice->total,
            ],
            [
                'code' => '130505'.$invoice->eps_id,
                'type' => 0,
                'description' => 'Campamento y otros tipos de hospedaje para EPS '.$invoice->eps->code .' - '.$invoice->eps->alias,
                'amount' => $invoice->total,
            ],
        ];

        AccountingNote::storeRecord($invoice, $pucs, $notes);

        return $invoice;
    }

    protected function updateRecord($request)
    {
        $invoice = $this->find($request->get('id'));

        if ($invoice) {
            $invoice->number = $request->get('number');
            $invoice->company_id = $request->get('company_id');
            $invoice->authorization_code = $request->get('authorization_code');
            $invoice->total = $request->get('total');
            $invoice->notes = $request->get('notes');
            $invoice->created_at = $request->get('created_at');
            $invoice->eps_id = 0;

            $authorization = Authorization::findByCode($request->get('authorization_code'));
            if ($authorization) {
                $invoice->eps_id = $authorization->eps_id;
            }

            $invoice->save();
            InvoiceLog::processUpdate($invoice, config('constants.invoices.action.edit'));

            $notes = "Factura para autorización ".$invoice->authorization_code." de la EPS: ".$invoice->eps->code
                ." - ".$invoice->eps->alias;

            $pucs = [
                [
                    'code' => '414010'.$invoice->eps_id,
                    'type' => 1,
                    'description' => 'Campamento y otros tipos de hospedaje para EPS '.$invoice->eps->code .' - '.$invoice->eps->alias,
                    'amount' => $invoice->total,
                ],
                [
                    'code' => '130505'.$invoice->eps_id,
                    'type' => 0,
                    'description' => 'Campamento y otros tipos de hospedaje para EPS '.$invoice->eps->code .' - '.$invoice->eps->alias,
                    'amount' => $invoice->total,
                ],
            ];

            AccountingNote::updateRecord($invoice, $pucs, $notes);

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
}
