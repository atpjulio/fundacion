<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EgressPuc extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'egress_id',
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
        return $this->belongsTo(Egress::class, 'egress_id', 'id');
    }

    /**
     * Methods
     */
    protected function storeRecord($egress, $pucs)
    {
        foreach ($pucs as $puc) {
            $this->create([
                'egress_id' => $egress->id,
                'code' => $puc['code'],
                'type' => $puc['type'],
                'description' => $puc['description'],
                'amount' => $puc['amount'],
                'created_at' => $egress->created_at,
            ]);
        }
    }

    protected function updateRecord($egress, $pucs)
    {
        $egressPucs = $this->where('egress_id', $egress->id)
            ->get();

        if ($egressPucs) {
            \DB::table('egress_pucs')->where('egress_id', $egress->id)
                ->delete();

            foreach ($pucs as $puc) {
                $this->create([
                    'egress_id' => $egress->id,
                    'code' => $puc['code'],
                    'type' => $puc['type'],
                    'description' => $puc['description'],
                    'amount' => $puc['amount'],
                    'created_at' => $egress->created_at,
                ]);
            }
        }
    }
}
