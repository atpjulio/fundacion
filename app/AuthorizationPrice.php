<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AuthorizationPrice extends Model
{
    use SoftDeletes;

    protected $fillable = [
      'authorization_id',
      'eps_id',
      'daily_price'
    ];
    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    protected function storeRecord($authorization, $request)
    {
        $this->create([
            'authorization_id' => $authorization->id,
            'eps_id' => $request->get('eps_id'),
            'daily_price' => $request->get('daily_price'),
        ]);
    }

    protected function updateRecord($authorization, $request)
    {
        if ($authorization->price) {
            $authorization->price->update([
                'eps_id' => $request->get('eps_id'),
                'daily_price' => $request->get('daily_price'),
            ]);
        } else {
            $this->create([
                'authorization_id' => $authorization->id,
                'eps_id' => $request->get('eps_id'),
                'daily_price' => $request->get('daily_price'),
            ]);
        }
    }

    protected function fixRecord($authorization, $dailyPrice)
    {
        if ($authorization->price) {
            $authorization->price->update([
                'eps_id' => $authorization->eps_id,
                'daily_price' => $dailyPrice,
            ]);
        } else {
            $this->create([
                'authorization_id' => $authorization->id,
                'eps_id' => $authorization->eps_id,
                'daily_price' => $dailyPrice,
            ]);
        }
    }
}
