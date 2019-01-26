<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Egress extends Model
{
	use SoftDeletes;

    protected $fillable = [
        'company_id',
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

    public function entity()
    {
        return $this->hasOne(Entity::class, 'id', 'entity_id');
    }

    /**
     * Dynamic attributes
     */
    public function getNumberAttribute()
    {
//        $record = $this->latest()->first();


        return 'G-'.\Carbon\Carbon::parse($this->created_at)->format("Ym").sprintf("%05d", $this->id);
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
            'company_id' => 1,
            'amount' => $amount,
            'entity_id' => $request->get('entity_id'),
            'concept' => $request->get('concept'),
            'created_at' => $request->get('created_at'),
        ]);

        EgressPuc::storeRecord($egress, $pucs);
    }

    protected function updateRecord($pucs, $request, $amount, $id)
    {
        $egress = $this->find($id);

        if ($egress) {
            $egress->update([
                'company_id' => 1,
                'amount' => $amount,
                'entity_id' => $request->get('entity_id'),
                'concept' => $request->get('concept'),
                'created_at' => $request->get('created_at'),
            ]);

            EgressPuc::updateRecord($egress, $pucs);
        }

        return $egress;
    }

}
