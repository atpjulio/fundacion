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
        'companion_name',
        'invoice_id',
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

    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
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

    public function getCodecAttribute()
    {
        if (strpos($this->code, config('constants.unathorized.prefix')) === FALSE) {
            return $this->code;
        }
        return '';
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
        if (!$request->get('code')) {
            $lastRecord = $this->orderBy('id', 'desc')
                ->first();

            $authorization->code = 'SA'.sprintf("%05d", 1);
            if ($lastRecord) {
                $authorization->code = 'SA'.sprintf("%05d", 1 + $lastRecord->id);
            }
        }

        $authorization->date_from = $request->get('date_from');
        $authorization->date_to = \Carbon\Carbon::parse($request->get('date_from'))->addDays($request->get('total_days'))->format("Y-m-d");
        $authorization->notes = $request->get('notes');
        $authorization->status = config('constants.status.active');
        $authorization->user_id = auth()->user()->id;
        $authorization->diagnosis = ucwords(mb_strtolower($request->get('diagnosis')));
        $authorization->location = config('constants.patient.location')[$request->get('location')];
        $authorization->companion = ($request->get('companion') == "1");
        if ($authorization->companion) {
            $authorization->companion_dni = $request->get('companion_dni');
            $authorization->companion_name = ucwords(mb_strtolower($request->get('companion_name')));
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
            $oldCode = $authorization->code;
            $authorization->code = $request->get('code');
            if ($authorization->codec == '' or empty($request->get('code'))) {
                $authorization->code = $oldCode;
            }

            $authorization->date_from = $request->get('date_from');
            $authorization->date_to = \Carbon\Carbon::parse($request->get('date_from'))->addDays($request->get('total_days'))->format("Y-m-d");
            $authorization->notes = $request->get('notes');
            $authorization->status = config('constants.status.active');
            $authorization->diagnosis = ucwords(mb_strtolower($request->get('diagnosis')));
            $authorization->location = config('constants.patient.location')[$request->get('location')];
            $authorization->companion = ($request->get('companion') == "1");
            if ($authorization->companion) {
                $authorization->companion_dni = $request->get('companion_dni');
                $authorization->companion_name = ucwords(mb_strtolower($request->get('companion_name')));
            }

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

    protected function findDepartures() 
    {
        return $this->where('date_to', \Carbon\Carbon::now()->format('Y-m-d'))
            ->where('status', config('constants.status.active'))
            ->get();
    }

    protected function full($search = '')
    {
        return $this::join('invoices', 'authorizations.invoice_id', '=', 'invoices.id')
            ->select('authorizations.*', 'invoices.number')
            ->where('authorizations.code', 'not like', config('constants.unathorized.prefix').'%')
            ->where('authorizations.code', 'like', '%'.$search.'%')
            ->orWhere('invoices.number', $search)
            ->orderBy('invoices.number', 'DESC')
            ->paginate(config('constants.pagination'));
    }

    protected function fullCount()
    {
        return $this->where('code', 'not like', config('constants.unathorized.prefix').'%')
            ->count();        
    }

    protected function incomplete()
    {
        return $this->where('code', 'like', config('constants.unathorized.prefix').'%')
            ->get();
    }

    protected function open()
    {
        return $this->where('invoice_id', 0)
            ->get();
    }

    protected function openForInvoices()
    {
        return $this->where('invoice_id', 0)
            ->where('code', 'not like', config('constants.unathorized.prefix').'%')
            ->get();
    }

    protected function close($search = '')
    {
        return $this::join('invoices', 'authorizations.invoice_id', '=', 'invoices.id')
            ->select('authorizations.*', 'invoices.number')
            ->where('authorizations.invoice_id', '<>', 0)
            ->where('authorizations.code', 'like', '%'.$search.'%')
            ->orWhere('invoices.number', $search)
            ->orderBy('invoices.number', 'DESC')
            ->paginate(config('constants.pagination'));
    }

    protected function closeCount()
    {
        return $this->where('invoice_id', '<>', 0)
            ->count();
    }

    protected function checkIfExists($code)
    {
        return $this->where("code", $code)
            ->first();
    }

}
