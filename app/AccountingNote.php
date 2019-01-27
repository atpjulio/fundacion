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
        'counter',
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
        return $this->hasMany(AccountingNotePuc::class, 'accounting_note_id');
    }

    /**
     * Dynamic attributes
     */
    public function getNumberAttribute()
    {
        return 'N-'.\Carbon\Carbon::parse($this->created_at)->format("Ym").sprintf("%05d", $this->counter);
    }

    /**
     * Methods
     */
    protected function storeRecord($invoice, $pucs, $notes = null, $amount)
    {
        $counter = $this->getCounter($invoice->created_at);

        $accountingNote = $this->create([
            'invoice_id' => $invoice->id,
            'amount' => $amount,
            'created_at' => $invoice->created_at,
            'notes' => $notes,
            'counter' => $counter,
        ]);

        AccountingNotePuc::storeRecord($accountingNote, $pucs);
    }

    protected function updateRecord($invoice, $pucs, $notes = null, $amount, $id = null)
    {
        if (!$id) {
            $accountingNote = $this->where('invoice_id', $invoice->id)
                ->first();            
        } else {
            $accountingNote = $this->findOrFail($id);
        }

        if ($accountingNote) {
            $counter = $accountingNote->counter ?: 1;
            $createdAt = $invoice->created_at;

            if (substr($invoice->created_at, 0, 7) != substr($accountingNote->created_at, 0, 7)) {
                $counter = $this->getCounter($createdAt);
            }

            $accountingNote->update([
                'invoice_id' => $invoice->id,
                'amount' => $amount,
                'created_at' => $invoice->created_at,
                'notes' => $notes,
                'counter' => $counter,
            ]);

            AccountingNotePuc::updateRecord($accountingNote, $pucs);
        }

        return $accountingNote;
    }

    protected function getCounter($createdAt)
    {
        $query = $this->where('created_at', 'like', '%'.substr($createdAt, 0, 7).'%')
            ->orderBy('created_at', 'desc')
            ->first();

        return $query ? $query->counter + 1 : 1;
    }

    protected function fixCounter($limit = 10)
    {
        $notes = $this->where('counter', 0)
            ->get();

        if (!$notes) {
            return 'No accounting notes to fix';
        }

        echo "\nCount: ".count($notes)."\n";

        $count = 0;
        foreach ($notes as $key => $note) {
            $note->counter = $this->getCounter($note->created_at);

            if (!$note->invoice_id) {
                $note->invoice_id = $note->id;
            }

            $note->save();
            $count++;

            if ($count == $limit) {
                break;
            }
        }

        return 'Processed '.$count.' note(s)';
    }

}
