<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Eps extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'code',
        'name',
        'nit',
        'daily_price',
        'alias',
        'contract',
        'policy',
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
            ->where('model_type', config('constants.modelType.eps'));
    }

    public function phone()
    {
        return $this->hasOne(Phone::class, 'model_id')
            ->where('model_type', config('constants.modelType.eps'));
    }
    
    /**
     * Methods
     */
    protected function storeRecord($request)
    {
        $eps = $this->create([
            'code' => strtoupper($request->get('code')),
            'name' => ucwords(strtolower($request->get('name'))),
            'nit' => $request->get('nit'),
            'daily_price' => $request->get('daily_price'),
            'alias' => ucwords(strtolower($request->get('alias'))),
            'contract' => $request->get('contract'),
            'policy' => $request->get('policy'),
        ]);

        $address = new Address();

        $address->model_type = config('constants.modelType.eps');
        $address->model_id = $eps->id;
        $address->address = ucwords(strtolower($request->get('address')));
        $address->address2 = ucwords(strtolower($request->get('address2')));
        $address->city = ucwords(strtolower($request->get('city')));
        $address->state = $request->get('state');

        $address->save();

        $phone = new Phone();

        $phone->model_type = config('constants.modelType.eps');
        $phone->model_id = $eps->id;
        $phone->phone = $request->get('phone');

        $phone->save();

        return $eps;
    }

    protected function updateRecord($request)
    {
        $eps = $this->find($request->get('id'));

        if ($eps) {
            $eps->update([
                'code' => strtoupper($request->get('code')),
                'name' => ucwords(strtolower($request->get('name'))),
                'nit' => $request->get('nit'),
                'daily_price' => $request->get('daily_price'),
                'alias' => ucwords(strtolower($request->get('alias'))),
                'contract' => $request->get('contract'),
                'policy' => $request->get('policy'),
            ]);

            $eps->address->update([
                'address' => ucwords(strtolower($request->get('address'))),
                'address2' => ucwords(strtolower($request->get('address2'))),
                'city' => ucwords(strtolower($request->get('city'))),
                'state' => $request->get('state'),
            ]);

            $eps->phone->update([
                'phone' => $request->get('phone'),
            ]);
        }

        return $eps;
    }
}
