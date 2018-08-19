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
        'guardianship',
        'guardianship_file',
        'companion',
        'companion_dni',
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

            $authorization->save();
        }

        return $authorization;
    }
}
