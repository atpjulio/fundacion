<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Receipt extends Model
{
	use SoftDeletes;

    protected $fillable = [
        'invoice_id',
        'amount',
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
    public function invoice()
    {
        return $this->hasOne(Invoice::class, 'id', 'invoice_id');
    }

    public function pucs()
    {
        return $this->hasMany(ReceiptPuc::class, 'accounting_note_id');
    }

    /*
	protected $fillable = [
        'invoice_id',
        'amount',
        'puc',
        'notes',
        'created_at'
	];
    */    

    /**
     * Dynamic attributes
     */

    /**
     * Methods
     */
    protected function storeRecord($invoice, $pucs, $notes = null, $amount)
    {
        $receipt = $this->create([
            'invoice_id' => $invoice->id,
            'amount' => $amount,
            'created_at' => $invoice->created_at,
            'notes' => $notes,
        ]);

        $invoice->payment += $receipt->amount;
        if ($invoice->payment >= $invoice->total) {
            $invoice->payment = $invoice->total;
            $invoice->status = config('constants.invoices.status.paid');
        }
        $invoice->save();

        ReceiptPuc::storeRecord($receipt, $pucs);
    }

    protected function updateRecord($invoice, $pucs, $notes = null, $amount)
    {
        $receipt = $this->where('invoice_id', $invoice->id)
            ->first();

        if ($receipt) {
            $oldAmount = $receipt->amount;

            $receipt->update([
                'invoice_id' => $invoice->id,
                'amount' => $amount,
                'created_at' => $invoice->created_at,
                'notes' => $notes,
            ]);

            $invoice->payment -= $oldAmount;
            $invoice->payment += $receipt->amount;
            $invoice->status = config('constants.invoices.status.pending');
            if ($invoice->payment >= $invoice->total) {
                $invoice->payment = $invoice->total;
                $invoice->status = config('constants.invoices.status.paid');
            } 
            $invoice->save();

            ReceiptPuc::updateRecord($receipt, $pucs);
        }

        return $receipt;
    }

    /**
     * Methods
     */
    /*
    protected function storeRecord($request) 
    {
        $invoice = Invoice::find($request->get('invoice_number'));
        if ($invoice) {
            $receipt = new Receipt();

            $receipt->invoice_id = $request->get('invoice_number');
            $receipt->puc_code = $request->get('puc_code');
            $receipt->puc_description = $request->get('puc_description');
            $receipt->amount = floatval($request->get('amount'));
            $receipt->notes = $request->get('notes');
            $receipt->created_at = $request->get('created_at');

            $receipt->save();            

            $invoice->payment += $receipt->amount;
            if ($invoice->payment >= $invoice->total) {
                $invoice->payment = $invoice->total;
                $invoice->status = config('constants.invoices.status.paid');
            }
            $invoice->save();
        }

        return $receipt;
    }

    protected function updateRecord($request, $id) 
    {
        $invoice = Invoice::find($request->get('invoice_number'));
        $receipt = $this->find($id);
        if ($invoice) {
            $oldAmount = $receipt->amount;

            $receipt->invoice_id = $request->get('invoice_number');
            $receipt->puc_code = $request->get('puc_code');
            $receipt->puc_description = $request->get('puc_description');
            $receipt->amount = floatval($request->get('amount'));
            $receipt->notes = $request->get('notes');
            $receipt->created_at = $request->get('created_at');

            $receipt->save();            

            $invoice->payment -= $oldAmount;
            $invoice->payment += $receipt->amount;
            $invoice->status = config('constants.invoices.status.pending');
            if ($invoice->payment >= $invoice->total) {
                $invoice->payment = $invoice->total;
                $invoice->status = config('constants.invoices.status.paid');
            } 
            $invoice->save();
        }

        return $receipt;
    }
    */
}
