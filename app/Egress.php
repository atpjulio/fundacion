<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\Balanceable;

class Egress extends Model
{
	use SoftDeletes, Balanceable;

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

    public function balance()
    {
        return $this->morphOne(Balance::class, 'balanceable');
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
            'created_at' => $createdAt,
            'counter' => $counter,
        ]);

        EgressPuc::storeRecord($egress, $pucs);
    }

    protected function updateRecord($pucs, $request, $amount, $id)
    {
        $egress = $this->find($id);
        if ($egress) {
            $counter = $egress->counter ?: 1;
            $createdAt = $request->get('created_at').' '.\Carbon\Carbon::now()->format('H:i:s');

            if (substr($request->get('created_at'), 0, 7) != substr($egress->created_at, 0, 7)) {
                $counter = $this->getCounter($createdAt);
            }

            while($this->checkIfExistsById($createdAt, $counter, $id)) {
                $counter++;
            }

            $egress->update([
                'company_id' => 1,  
                'amount' => $amount,
                'entity_id' => $request->get('entity_id'),
                'concept' => $request->get('concept'),
                'created_at' => $createdAt,
                'counter' => $counter,
            ]);

            EgressPuc::updateRecord($egress, $pucs);
        }

        return $egress;
    }

    protected function getCounter($createdAt)
    {
        $query = $this->where('created_at', 'like', '%'.substr($createdAt, 0, 7).'%')
            ->orderBy('counter', 'desc')
            ->first();

        return $query ? $query->counter + 1 : 1;
    }

    protected function searchRecords($search = '')
    {
        $query = $this::join('entities', 'egresses.entity_id', '=', 'entities.id')
            ->select('egresses.*', 'entities.name', 'entities.doc')
            ->where('entities.name', 'like', '%'.$search.'%')
            ->orWhere('entities.doc', 'like', '%'.$search.'%');

        if (is_numeric($search)) {
            if ($search > 9999) {
                $search = substr($search, 0, 4).'-'.substr($search, 4);
            }
            $query = $query->orWhere('egresses.created_at', 'like', $search.'%');
        }

        return $query
            // ->orderByRaw('date(substring(egresses.created_at, 0, 4)) desc')
            ->orderBy('egresses.created_at', 'DESC')
            ->orderBy('egresses.counter', 'DESC')
            ->paginate(config('constants.pagination'));
    }

    protected function checkIfExists($createdAt, $counter)
    {
        return $this->where('created_at', 'like', '%'.substr($createdAt, 0, 7).'%')
            ->where('counter', $counter)
            ->first();
    }

    protected function checkIfExistsById($createdAt, $counter, $id)
    {
        return $this->where('created_at', 'like', '%'.substr($createdAt, 0, 7).'%')
            ->where('id', '<>', $id)
            ->where('counter', $counter)
            ->first();
    }

    protected function getEgressesByDate($yearMonth)
    {
        return $this->whereBetween('created_at', [
            $yearMonth.'-01 00:00:00', $yearMonth.'-31 23:59:59'
        ])->get();
    }

    public function saveBalance($amount, $month, $year, $type)
    {
        $balance = $this->balance;
        if ($balance and $balance->type == $type) {
            return $this->updateBalance($balance, $amount, $month, $year, $type);
        }

        return $this->createBalance($this, $amount, $month, $year, $type);
    }

}
