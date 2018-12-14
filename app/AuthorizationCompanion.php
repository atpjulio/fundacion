<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AuthorizationCompanion extends Model
{
    use SoftDeletes;

    protected $fillable = [
    'authorization_id',
    'eps_service_id',
    'dni_type',
    'dni',
    'name',
    'phone',
    'notes'
    ];
    /**
    * The attributes that should be mutated to dates.
    *
    * @var array
    */
    protected $dates = ['deleted_at'];

    public function authorization()
    {
    return $this->belongsTo(Authorization::class);
    }

    protected function storeRecord($authorizationId, $request)
    {
        foreach ($request->get('companion_dni') as $key => $dni) {
          AuthorizationCompanion::create([
              'authorization_id' => $authorizationId,
              'eps_service_id' =>  $request->get('eps_service_id'),
              'dni' => $dni,
              'name' => ucwords(mb_strtolower($request->get('companion_name')[$key])),
              'phone' => $request->get('companion_phone')[$key],
          ]);
        }
    }

    protected function updateRecord($authorizationId, $request)
    {
        \DB::table('authorization_companions')
            ->where('authorization_id', $authorizationId)
            ->delete();
        $this->storeRecord($authorizationId, $request);
    }
}
