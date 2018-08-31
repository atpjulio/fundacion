<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AccountingNote extends Model
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
        return $this->hasMany(AccountingNotePuc::class, 'id');
    }

    /**
     * Dynamic attributes
     */
//    public function getMovementsAttributes()
//    {
//
//    }

    /**
     * Methods
     */
    protected function storeRecord($invoice, $pucs, $notes = null)
    {
        $accountingNote = $this->create([
            'invoice_id' => $invoice->id,
            'amount' => $invoice->total,
            'created_at' => $invoice->created_at,
            'notes' => $notes,
        ]);

        AccountingNotePuc::storeRecord($accountingNote, $pucs);
    }

    protected function updateRecord($invoice, $pucs, $notes = null)
    {
        $accountingNote = $this->where('invoice_id', $invoice->id)
            ->first();

        if ($accountingNote) {
            $accountingNote->update([
                'invoice_id' => $invoice->id,
                'amount' => $invoice->total,
                'created_at' => $invoice->created_at,
                'notes' => $notes,
            ]);

            AccountingNotePuc::updateRecord($accountingNote, $pucs);
        }

        return $accountingNote;
    }
}
