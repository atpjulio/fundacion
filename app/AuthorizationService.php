<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\EpsService;

class AuthorizationService extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'authorization_id',
        'eps_service_id',
        'price',
        'days',
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

    public function service()
    {
        return $this->hasOne(EpsService::class, 'id', 'eps_service_id');
    }

    protected function storeRecord($authorization, $request)
    {
        $this->create([
          'authorization_id' => $authorization->id,
          'eps_service_id' => $request->get('eps_service_id'),
          'price' => $request->get('daily_price'),
          'days' => $request->get('total_days'),
        ]);

        if ($request->get('service_code') and is_array($request->get('service_code'))) {
          foreach ($request->get('service_code') as $key => $serviceCode) {
              $currentService = EpsService::getService($authorization->eps_id, $serviceCode);
              if ($currentService) {
                  $this->create([
                      'authorization_id' => $authorization->id,
                      'eps_service_id' => $currentService->id,
                      'price' => $currentService->price,
                      'days' => $request->get('service_days')[$key] ?: $request->get('total_days'),
                  ]);
              }
          }
        }
    }

    protected function updateRecord($authorization, $request)
    {
        \DB::table('authorization_services')->where('authorization_id', $authorization->id)
            ->delete();
        $this->create([
            'authorization_id' => $authorization->id,
            'eps_service_id' => $request->get('eps_service_id'),
            'price' => $request->get('daily_price'),
            'days' => $request->get('total_days'),
        ]);

        if ($request->get('service_code') and is_array($request->get('service_code'))) {
            foreach ($request->get('service_code') as $key => $serviceCode) {
                $currentService = EpsService::getService($authorization->eps_id, $serviceCode);
                if ($currentService) {
                    $this->create([
                        'authorization_id' => $authorization->id,
                        'eps_service_id' => $currentService->id,
                        'price' => $currentService->price,
                        'days' => $request->get('service_days')[$key] ?: $request->get('total_days'),
                    ]);
                }
            }
        }
    }

    protected function fixRecord($authorization, $days)
    {
        $record = $this->where('authorization_id', $authorization->id)
            ->first();
        if (!$record) {
            return false;            
        }
            
        $service = EpsService::find($record->eps_service_id);
        if (!$service) {
            return false;
        }

        $record->update([
            'days' => $days,
            'price' => $service->price
        ]);
    }

    protected function fixAuthorizationService($authorization, $epsServiceId, $days)
    {
        $service = EpsService::where('code', $epsServiceId)
            ->first();
        if (!$service) {
            return 'service not found';
        }

        $record = $this->where('authorization_id', $authorization->id)
            ->where('eps_service_id', $service->id)
            ->first();

        if (!$record) {
            return 'record not found';            
        }

        $record->update([
            'days' => $days,
            'price' => $service->price
        ]);

        return 'received: '.$days.' then '.$record->days.' days, update performed on id: '.$record->id;
    }

    protected function checkIfExists($authorization) 
    {
        return $this->where('authorization_id', $authorization->id)
            ->first();
    }
}
