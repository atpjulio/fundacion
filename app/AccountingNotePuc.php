<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AccountingNotePuc extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'accounting_note_id',
        'code',
        'type',
        'description',
        'amount',
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
    public function accountingNote()
    {
        return $this->belongsTo(AccountingNote::class, 'accounting_note_id', 'id');
    }

    /**
     * Methods
     */
    protected function storeRecord($accountingNote, $pucs)
    {
        foreach ($pucs as $puc) {
            $this->create([
                'accounting_note_id' => $accountingNote->id,
                'code' => $puc['code'],
                'type' => $puc['type'],
                'description' => $puc['description'],
                'amount' => $puc['amount'],
                'created_at' => $accountingNote->created_at,
            ]);
        }
    }

    protected function updateRecord($accountingNote, $pucs)
    {
        $accountingNotePucs = $this->where('accounting_note_id', $accountingNote->id)
            ->get();

        if ($accountingNotePucs) {
            \DB::table('accounting_note_pucs')->where('accounting_note_id', $accountingNote->id)
                ->delete();

            foreach ($pucs as $puc) {
                $this->create([
                    'accounting_note_id' => $accountingNote->id,
                    'code' => $puc['code'],
                    'type' => $puc['type'],
                    'description' => $puc['description'],
                    'amount' => $puc['amount'],
                    'created_at' => $accountingNote->created_at,
                ]);
            }
        }
    }
}
