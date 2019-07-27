<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Phone extends Model
{
    protected $fillable = [
        'model_id',
        'model_type', // 1 = Company, 2 = Eps, 3 = Patient
        'phone',
        'phone2',
    ];

    public function getFullPhoneAttribute()
    {
        if (!$this->phone2) {
            return $this->phone;
        }
        return $this->phone.' - '.$this->phone2;
    }

}
