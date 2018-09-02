<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Authorization extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'eps_id',
        'eps_service_id',
        'patient_id',
        'code',
        'date_from',
        'date_to',
        'total',
        'companion',
        'companion_dni',
        'guardianship',
        'guardianship_file',
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
    public function eps()
    {
        return $this->hasOne(Eps::class, 'id', 'eps_id');
    }

    public function service()
    {
        return $this->hasOne(EpsService::class, 'id', 'eps_service_id');
    }

    public function patient()
    {
        return $this->hasOne(Patient::class, 'id', 'patient_id');
    }

    /**
     * Methods
     */
    protected function storeRecord($request)
    {
        $authorization = new Authorization();

        $authorization->eps_id = $request->get('eps_id');
        $authorization->eps_service_id = $request->get('eps_service_id');
        $authorization->patient_id = $request->get('patient_id');
        $authorization->code = $request->get('code');
        $authorization->date_from = $request->get('date_from');
        $authorization->date_to = $request->get('date_to');
        $authorization->notes = $request->get('notes');
        $authorization->companion = ($request->get('companion') == "Si");
        if ($authorization->companion) {
            $authorization->companion_dni = join(",", $request->get('companionDni'));
        }

        $authorization->save();

        return $authorization;
    }

    protected function updateRecord($request)
    {
        $authorization = $this->find($request->get('id'));

        if ($authorization) {
            $authorization->eps_id = $request->get('eps_id');
            $authorization->eps_service_id = $request->get('eps_service_id');
            $authorization->patient_id = $request->get('patient_id');
            $authorization->code = $request->get('code');
            $authorization->date_from = $request->get('date_from');
            $authorization->date_to = $request->get('date_to');
            $authorization->notes = $request->get('notes');
            $authorization->companion = $request->get('companion');
            $authorization->companion_dni = $authorization->companion ? join(",", $request->get('companionDni')) : null;

            $authorization->save();
        }

        return $authorization;
    }

    protected function findByCode($code)
    {
        return $this->where('code', $code)->first();
    }
}
