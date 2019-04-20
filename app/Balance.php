<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Balance extends Model
{
    protected $fillable = [
        'balanceable_id',
        'balanceable_type',
        'amount',
        'month',
        'year',
        'type',
    ];

    public function balanceable()
    {
        return $this->morphTo();
    }

}
