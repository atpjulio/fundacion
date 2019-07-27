<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    protected $fillable = [
        'model_id',
        'model_type', // 1 = Company, 2 = Eps, 3 = Patient
        'address',
        'address2',
        'zip',
        'city',
        'state',
        'country',
    ];

    public function getFullAddressAttribute()
    {
        if (!$this->address2) {
            return $this->address;
        }
        return $this->address.' '.$this->address2;
    }
}
