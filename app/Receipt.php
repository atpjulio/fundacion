<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Receipt extends Model
{
	use SoftDeletes;

    protected $fillable = [
        'entity_id',        
        'amount',
        'concept',
        'created_at',
    ];
    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    /**
     * Relationships
     */
    public function entity()
    {
        return $this->hasOne(Entity::class, 'id', 'entity_id');
    }

    public function invoice()
    {
        return $this->hasOne(Invoice::class, 'id', 'invoice_id');
    }

    public function pucs()
    {
        return $this->hasMany(ReceiptPuc::class, 'receipt_id');
    }

    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

    /**
     * Dynamic attributes
     */
    public function getNumberAttribute()
    {
        return 'R-'.date("Ym").sprintf("%05d", $this->id);
    }

    /**
     * Methods
     */
    protected function storeRecord($request, $pucs, $amount)
    {
        $receipt = $this->create([
            'entity_id' => $request->get('entity_id'),
            'concept' => $request->get('concept'),
            'amount' => $amount,
            'created_at' => $request->get('created_at'),
        ]);

        ReceiptPuc::storeRecord($receipt, $pucs);
    }

    protected function updateRecord($request, $pucs, $amount, $id)
    {
        $receipt = $this->find($id);

        if ($receipt) {
            $receipt->update([
                'entity_id' => $request->get('entity_id'),
                'concept' => $request->get('concept'),
                'amount' => $amount,
                'created_at' => $request->get('created_at'),
            ]);

            ReceiptPuc::updateRecord($receipt, $pucs);
        }

        return $receipt;
    }

}
