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
        'companion_eps_service_id',
        'guardianship',
        'guardianship_file',
        'notes',
        'diagnosis',
        'location',
        'status',
        'user_id',
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

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    /**
     * Attributes
     */
    public function getDaysAttribute()
    {
        return \Carbon\Carbon::parse($this->date_to)->diffInDays(\Carbon\Carbon::parse($this->date_from));        
    }

    public function getPersonsAttribute()
    {
        if ($this->companion_dni) {
            return count(explode(",", $this->companion_dni)) + 1;
        }
        return 1;
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
        $authorization->date_to = \Carbon\Carbon::parse($request->get('date_from'))->addDays($request->get('total_days'))->format("Y-m-d");
        $authorization->notes = $request->get('notes');
        $authorization->companion = ($request->get('companion') == "Si");
        $authorization->status = config('constants.status.active');
        $authorization->user_id = auth()->user()->id;
        if ($authorization->companion) {
            $authorization->companion_dni = join(",", $request->get('companionDni'));
            $authorization->companion_eps_service_id = join(",", $request->get('companionServiceId'));
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
            $authorization->date_to = \Carbon\Carbon::parse($request->get('date_from'))->addDays($request->get('total_days'))->format("Y-m-d");
            $authorization->notes = $request->get('notes');
            $authorization->companion = ($request->get('companion') == "Si");
            $authorization->companion_dni = $authorization->companion ? join(",", $request->get('companionDni')) : null;
            $authorization->companion_eps_service_id = $authorization->companion ? join(",", $request->get('companionServiceId')) : null;
            $authorization->status = config('constants.status.active');
            $authorization->user_id = auth()->user()->id;

            $authorization->save();

            $invoice = Invoice::getInvoiceByAuthorizationCode($authorization->code);
            if ($invoice) {
                $invoice->update([
                    'total' => $authorization->eps->daily_price * $authorization->days
                ]);
            }
        }

        return $authorization;
    }

    protected function findByCode($code)
    {
        return $this->where('code', $code)->first();
    }
}
