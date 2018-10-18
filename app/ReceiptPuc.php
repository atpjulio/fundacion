<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ReceiptPuc extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'receipt_id',
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
    public function receipt()
    {
        return $this->belongsTo(Receipt::class, 'receipt_id', 'id');
    }

    /**
     * Methods
     */
    protected function storeRecord($receipt, $pucs)
    {
        foreach ($pucs as $puc) {
            $this->create([
                'receipt_id' => $receipt->id,
                'code' => $puc['code'],
                'type' => $puc['type'],
                'description' => $puc['description'],
                'amount' => $puc['amount'],
                'created_at' => $receipt->created_at,
            ]);
        }
    }

    protected function updateRecord($receipt, $pucs)
    {
        $receiptPucs = $this->where('receipt_id', $receipt->id)
            ->get();

        if ($receiptPucs) {
            \DB::table('receipt_pucs')->where('receipt_id', $receipt->id)
                ->delete();

            foreach ($pucs as $puc) {
                $this->create([
                    'receipt_id' => $receipt->id,
                    'code' => $puc['code'],
                    'type' => $puc['type'],
                    'description' => $puc['description'],
                    'amount' => $puc['amount'],
                    'created_at' => $receipt->created_at,
                ]);
            }
        }
    }
}
