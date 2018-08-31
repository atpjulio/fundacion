<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class InvoiceLog extends Model
{
    protected $fillable = [
        'number',
        'company_id',
        'authorization_code',
        'eps_id',
        'total',
        'payment',
        'action',
        'status',
        'notes',
    ];

    public function wasAnyChange($invoice) {
        return $this->company_id != $invoice->company_id or $this->authorization_code != $invoice->authorization_code
            or $this->eps_id != $invoice->eps_id or $this->total != $invoice->total or $this->notes != $invoice->notes;
    }

    protected function storeRecord($request, $action)
    {
        $invoice = new InvoiceLog();

        $invoice->number = $request->get('number');
        $invoice->company_id = $request->get('company_id');
        $invoice->authorization_code = $request->get('authorization_code');
        $invoice->total = $request->get('total');
        $invoice->payment = $request->get('payment') ?: 0;
        $invoice->action = $action;
        $invoice->notes = $request->get('notes');
        $invoice->eps_id = 0;

        $authorization = Authorization::findByCode($request->get('authorization_code'));
        if ($authorization) {
            $invoice->eps_id = $authorization->eps_id;
        }

        $invoice->save();

        return $invoice;
    }

    protected function processUpdate($invoice, $action)
    {
        $row = $this->where('number', $invoice->number)
            ->get()
            ->last();

        if ($row and $row->wasAnyChange($invoice)) {
            $previousPayments = $row->payment;
            $row = new InvoiceLog();

            $row->number = $invoice->number;
            $row->company_id = $invoice->company_id;
            $row->authorization_code = $invoice->authorization_code;
            $row->total = $invoice->total;
            $row->payment = $previousPayments;
            $row->action = $action;
            $row->notes = $invoice->notes;
            $row->eps_id = $invoice->eps_id;

            $row->save();
        }

        return $row;
    }

    protected function updateRecord($request, $action)
    {
        $invoice = $this->find($request->get('id'));

        if ($invoice) {
            $invoice->number = $request->get('number');
            $invoice->company_id = $request->get('company_id');
            $invoice->authorization_code = $request->get('authorization_code');
            $invoice->total = $request->get('total');
            $invoice->payment = $request->get('payment') ?: 0;
            $invoice->action = $action;
            $invoice->notes = $request->get('notes');
            $invoice->eps_id = 0;

            $authorization = Authorization::findByCode($request->get('authorization_code'));
            if ($authorization) {
                $invoice->eps_id = $authorization->eps_id;
            }

            $invoice->save();
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
