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
        return \Carbon\Carbon::parse($this->authorization->date_to)->diffInDays(\Carbon\Carbon::parse($this->authorization->date_from));        
    }

    public function getFormatNumberAttribute()
    {
        return sprintf("%05d", $this->number);        
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
            // $invoice->total *=  $authorization->persons;
        }

        $invoice->save();

        InvoiceLog::storeRecord($request, config('constants.invoices.action.create'));

        $notes = "Factura para autorización ".$invoice->authorization_code." de la EPS: ".$invoice->eps->code
            ." - ".$invoice->eps->alias;

        $pucs = [
            [
                'code' => '414010'.sprintf("%02d", $invoice->eps_id),
                'type' => 1,
                'description' => 'Campamento y otros tipos de hospedaje para EPS '.$invoice->eps->code .' - '.$invoice->eps->alias,
                'amount' => $invoice->total,
            ],
            [
                'code' => '130505'.sprintf("%02d", $invoice->eps_id),
                'type' => 0,
                'description' => 'Campamento y otros tipos de hospedaje para EPS '.$invoice->eps->code .' - '.$invoice->eps->alias,
                'amount' => $invoice->total,
            ],
        ];

        AccountingNote::storeRecord($invoice, $pucs, $notes, $invoice->total);

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
                // $invoice->total *=  $authorization->persons;
            }

            $invoice->save();
            InvoiceLog::processUpdate($invoice, config('constants.invoices.action.edit'));

            $notes = "Factura para autorización ".$invoice->authorization_code." de la EPS: ".$invoice->eps->code
                ." - ".$invoice->eps->alias;

            $pucs = [
                [
                    'code' => '414010'.sprintf("%02d", $invoice->eps_id),
                    'type' => 1,
                    'description' => 'Campamento y otros tipos de hospedaje para EPS '.$invoice->eps->code .' - '.$invoice->eps->alias,
                    'amount' => $invoice->total,
                ],
                [
                    'code' => '130505'.sprintf("%02d", $invoice->eps_id),
                    'type' => 0,
                    'description' => 'Campamento y otros tipos de hospedaje para EPS '.$invoice->eps->code .' - '.$invoice->eps->alias,
                    'amount' => $invoice->total,
                ],
            ];

            AccountingNote::updateRecord($invoice, $pucs, $notes, $invoice->total);

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
}
