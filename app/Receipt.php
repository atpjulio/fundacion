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
        'counter'
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
        return 'R-'.\Carbon\Carbon::parse($this->created_at)->format("Ym").sprintf("%05d", $this->counter);
    }

    /**
     * Methods
     */
    protected function storeRecord($request, $pucs, $amount)
    {
        $counter = $this->getCounter($request->get('created_at'));

        $receipt = $this->create([
            'entity_id' => $request->get('entity_id'),
            'concept' => $request->get('concept'),
            'amount' => $amount,
            'created_at' => $request->get('created_at'),
            'counter' => $counter,
        ]);

        ReceiptPuc::storeRecord($receipt, $pucs);
    }

    protected function updateRecord($request, $pucs, $amount, $id)
    {
        $receipt = $this->find($id);

        if ($receipt) {
            $counter = $receipt->counter ?: 1;
            $createdAt = $request->get('created_at');

            if (substr($request->get('created_at'), 0, 7) != substr($receipt->created_at, 0, 7)) {
                $counter = $this->getCounter($createdAt);
            }

            $receipt->update([
                'entity_id' => $request->get('entity_id'),
                'concept' => $request->get('concept'),
                'amount' => $amount,
                'created_at' => $request->get('created_at'),
                'counter' => $counter,
            ]);

            ReceiptPuc::updateRecord($receipt, $pucs);
        }

        return $receipt;
    }

    protected function storeRecordFromTxt($line)
    {
        $data = explode(",", $line);
        $epsCode = $data[0];
        $createdAt = isset($data[1]) ? \Carbon\Carbon::parse($data[1])->format("Y-m-d").' '.\Carbon\Carbon::now()->format('H:i:s') : '';
        $number = isset($data[2]) ? $data[2] : '';
        $amount = isset($data[3]) ? $data[3] : '';

        $eps = Eps::checkIfExists($epsCode);
        if (!$eps) {
            return null;
        }

        $entity = Entity::checkIfExists($eps->nit);
        if (!$entity) {
            return null;
        }
        $counter = $this->getCounter($createdAt);

        while($this->checkIfExists($createdAt, $counter)) {
            $counter++;
        }

        $receipt = $this->create([
            'entity_id' => $entity->id,
            'concept' => 'Pago de factura '.$number.' de '.$eps->name,
            'amount' => floatval($amount),
            'created_at' => $createdAt,
            'counter' => $counter,
        ]);

        $pucs = [
            [
                'code' => '111005',
                'type' => 0,
                'description' => 'Bancoomeva',
                'amount' => $amount,
            ],
            [
                'code' => '130505',
                'type' => 1,
                'description' => $eps->alias,
                'amount' => $amount,
            ],
        ];

        ReceiptPuc::storeRecord($receipt, $pucs);

        return $receipt;
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
        $receipts = $this->where('counter', 0)
            ->get();

        if (!$receipts) {
            return 'No receipts to fix';
        }

        echo "\nCount: ".count($receipts)."\n";

        $count = 0;
        foreach ($receipts as $key => $receipt) {
            $receipt->counter = $this->getCounter($receipt->created_at);
            $receipt->created_at = \Carbon\Carbon::parse($receipt->created_at)->format("Y-m-d").' '.\Carbon\Carbon::now()->format('H:i:s');
            $receipt->save();
            $count++;

            if ($count == $limit) {
                break;
            }
        }

        return 'Processed '.$count.' receipt(s)';
    }

    protected function searchRecords($search = '')
    {
        $query = $this::join('entities', 'receipts.entity_id', '=', 'entities.id')
            ->select('receipts.*', 'entities.name', 'entities.doc')
            ->where('entities.name', 'like', '%'.$search.'%');

        if (is_numeric($search)) {
            if ($search > 9999) {
                $search = substr($search, 0, 4).'-'.substr($search, 4);
            }
            $query = $query->orWhere('receipts.created_at', 'like', $search.'%');
        }

        return $query
            ->orderBy('receipts.created_at', 'DESC')
            ->paginate(config('constants.pagination'));
    }

    protected function checkIfExists($createdAt, $counter)
    {
        return $this->where('created_at', 'like', '%'.substr($createdAt, 0, 7).'%')
            ->where('counter', $counter)
            ->first();
    }

}
