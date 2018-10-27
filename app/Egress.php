<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Egress extends Model
{
	use SoftDeletes;

    protected $fillable = [
        'company_id',
        'bank_id',
        'amount',
        'payment_type',
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
    public function pucs()
    {
        return $this->hasMany(EgressPuc::class, 'egress_id');
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
        return sprintf("%05d", $this->id);
    }

    public function getBankAttribute()
    {
        return config('constants.banks')[$this->bank_id];
    }

    public function getPaymentAttribute()
    {
        return config('constants.paymentType')[$this->payment_type];
    }

    /**
     * Methods
     */
    protected function storeRecord($pucs, $request, $amount)
    {
        $egress = $this->create([
            'company_id' => $request->get('company_id'),
            'amount' => $amount,
            'bank_id' => $request->get('bank_id'),
            'payment_type' => $request->get('payment_type'),
            'notes' => $request->get('notes'),
        ]);

        EgressPuc::storeRecord($egress, $pucs);
    }

    protected function updateRecord($pucs, $request, $amount, $id)
    {
        $egress = $this->find($id);

        if ($egress) {
            $egress->update([
                'company_id' => $request->get('company_id'),
                'amount' => $amount,
                'created_at' => $egress->created_at,
                'bank_id' => $request->get('bank_id'),
                'payment_type' => $request->get('payment_type'),
                'notes' => $request->get('notes'),
            ]);

            EgressPuc::updateRecord($egress, $pucs);
        }

        return $egress;
    }

}
