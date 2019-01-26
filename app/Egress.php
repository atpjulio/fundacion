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
        return 'G-'.\Carbon\Carbon::parse($this->created_at)->format("Ym").sprintf("%05d", $this->counter);
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
        $createdAt = $request->get('created_at').' '.\Carbon\Carbon::now()->format('H:i:s');
        $counter = $this->getCounter($createdAt);
        
        $egress = $this->create([
            'company_id' => 1,
            'amount' => $amount,
            'entity_id' => $request->get('entity_id'),
            'concept' => $request->get('concept'),
            'created_at' => $request->get('created_at'),
            'counter' => $counter,
        ]);

        EgressPuc::storeRecord($egress, $pucs);
    }

    protected function updateRecord($pucs, $request, $amount, $id)
    {
        $egress = $this->find($id);
        if ($egress) {
            $counter = $egress->counter ?: 1;
            if ($request->get('created_at') != substr($egress->created_at, 0, 10)) {
                $counter = $this->getCounter($request->get('created_at').' '.\Carbon\Carbon::now()->format('H:i:s'));
            }

            $egress->update([
                'company_id' => 1,
                'amount' => $amount,
                'entity_id' => $request->get('entity_id'),
                'concept' => $request->get('concept'),
                'created_at' => $request->get('created_at'),
                'counter' => $counter,
            ]);

            EgressPuc::updateRecord($egress, $pucs);
        }

        return $egress;
    }

    protected function getCounter($createdAt)
    {
        $query = $this->where('created_at', 'like', '%'.substr($createdAt, 0, 7).'%')
            ->orderBy('created_at', 'desc')
            ->first();

        return $query ? $query->counter + 1 : 1;
    }
}
