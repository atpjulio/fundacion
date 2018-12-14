<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

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

    public function getTotalPatientsAttribute()
    {
        return count(Patient::getPatientsForEps($this->id));
    }

    public function price()
    {
      return $this->hasMany(EpsPrice::class);
    }

    /**
    * Dynamic attributes
    */
    public function getDailyPricesAttribute()
    {
        $totals = [];
        foreach ($this->price as $key => $epsPrice) {
            $totals[] = number_format($epsPrice->daily_price, 2, ",", ".");
        }

        return $totals;
    }
    /**
     * Methods
     */
    protected function storeRecord($request)
    {
        try {
          DB::beginTransaction();

          $eps = $this->create([
              'code' => strtoupper($request->get('code')),
              'name' => ucwords(strtolower($request->get('name'))),
              'nit' => $request->get('nit'),
              'daily_price' => 0,
              'alias' => ucwords(strtolower($request->get('alias'))),
              'contract' => $request->get('contract'),
              'policy' => $request->get('policy'),
          ]);

          foreach ($request->get('prices') as $key => $price) {
            EpsPrice::create([
              'eps_id' => $eps->id,
              'daily_price' => $price,
              'name' => $request->get('names')[$key],
            ]);
          }

          Entity::storeRecord($request);

          DB::commit();
        } catch(\Exception $e) {
          DB::rollback();
          dd($e);
        }

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
          try {
            DB::beginTransaction();

            $eps->update([
                'code' => strtoupper($request->get('code')),
                'name' => ucwords(strtolower($request->get('name'))),
                'nit' => $request->get('nit'),
                'daily_price' => 0,
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

            \DB::table('eps_prices')->where('eps_id', $eps->id)
                ->delete();
            foreach ($request->get('prices') as $key => $price) {
              EpsPrice::create([
                'eps_id' => $eps->id,
                'daily_price' => $price,
                'name' => $request->get('names')[$key],
              ]);
            }

            Entity::updateRecord($request, $eps->nit);

            DB::commit();
          } catch(\Exception $e) {
            DB::rollback();
            dd($e);
          }

        }

        return $eps;
    }

    protected function checkIfExists($code)
    {
        return $this->where("code", strtoupper($code))
            ->first();
    }

}
