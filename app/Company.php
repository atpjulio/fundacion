<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Company extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'nit',
        'billing_resolution',
        'billing_date',
        'billing_start',
        'billing_end',
        'alias',
        'logo',
        'notes',
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
    public function address()
    {
        return $this->hasOne(Address::class, 'model_id')
            ->where('model_type', config('constants.modelType.company'));
    }

    public function phone()
    {
        return $this->hasOne(Phone::class, 'model_id')
            ->where('model_type', config('constants.modelType.company'));
    }
}
