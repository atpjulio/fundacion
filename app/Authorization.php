<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;
use App\Patient;
use App\EpsService;
use App\AuthorizationDate;
use App\AuthorizationPrice;
use App\AuthorizationService;
use App\AuthorizationCompanion;
use Illuminate\Http\Request;

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
        'companion_phone',
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
        'multiple',
        'multiple_services'
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

    public function services()
    {
      return $this->hasMany(AuthorizationService::class);
    }

    public function dates()
    {
      return $this->hasMany(AuthorizationDate::class);
    }

    public function companions()
    {
      return $this->hasMany(AuthorizationCompanion::class);
    }

    public function price()
    {
      return $this->hasOne(AuthorizationPrice::class);
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

    public function getTotalServicesAttribute()
    {
        return array_sum($this->services->pluck('price')->toArray()) * 
            array_sum($this->services->pluck('days')->toArray());
    }

    public function getCodecAttribute()
    {
        if (strpos($this->code, config('constants.unathorized.prefix')) === FALSE) {
            return $this->code;
        }
        return '';
    }

    public function getDailyPriceAttribute()
    {
        $dailyPrice = 0;
        if ($this->price) {
            $dailyPrice = $this->price->daily_price;
        } elseif (count($this->eps->price) > 0) {
            $dailyPrice = $this->eps->price[0]->daily_price;
        } elseif ($this->eps->daily_price > 0) {
            $dailyPrice = $this->eps->daily_price;
        }

        if ($this->multiple) {
            if (count($this->services) > 0) {
                $dailyPrice = 0;
                foreach ($this->services as $service) {
                    $dailyPrice += $service->price * $service->days / $this->services[0]->days;
                }
            } else {
                $dailyPrice += count(explode(',', $this->multiple_services)) * $this->eps->daily_price;
            }
        }
        return $dailyPrice;
    }

    /**
     * Methods
     */
    protected function storeRecord($request)
    {
        try {
            DB::beginTransaction();

            $authorization = new Authorization();

            $authorization->eps_id = $request->get('eps_id');
            $authorization->eps_service_id = $request->get('eps_service_id');
            $authorization->patient_id = $request->get('patient_id');
            $authorization->notes = $request->get('notes');
            $authorization->status = config('constants.status.active');
            $authorization->user_id = auth()->user()->id;
            $authorization->diagnosis = ucwords(mb_strtolower($request->get('diagnosis')));
            $authorization->location = config('constants.patient.location')[$request->get('location')];
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
            $authorization->companion = ($request->get('companion') == "1");

            $authorization->multiple = 0;
            if ($request->get('multiple_services')) {
                $authorization->multiple = config('constants.status.active');
            }
            $authorization->save();

            if ($authorization->companion) {
                AuthorizationCompanion::storeRecord($authorization->id, $request);
            }
            AuthorizationPrice::storeRecord($authorization, $request);
            AuthorizationDate::storeRecord($authorization, $request);
            AuthorizationService::storeRecord($authorization, $request);
            Patient::createOrUpdatePhone($authorization->patient_id, $request);

            DB::commit();
        } catch(\Exception $e) {
            DB::rollback();
            dd($e);
        }

        return $authorization;
    }

    protected function updateRecord($request)
    {
        $authorization = $this->find($request->get('id'));

        if ($authorization) {
            try {
                DB::beginTransaction();

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

                $authorization->multiple = 0;
                if ($request->get('multiple_services')) {
                    $authorization->multiple = config('constants.status.active');
                    $authorization->multiple_services = null;
                }

                if ($authorization->invoice_id > 0) {
                    $invoice = $authorization->invoice;
                    if ($invoice->multiple) {
                        $invoiceCodes = json_decode($invoice->multiple_codes, true);
                        foreach ($invoiceCodes as $index => $code) {
                            if ($code == $oldCode) {
                                $invoiceCodes[$index] = $authorization->code;
                                break;
                            }
                        }
                        $invoice->multiple_codes = json_encode($invoiceCodes);
                    } else {
                        $invoice->authorization_code = $authorization->code;
                    }
                    $invoice->save();
                }
                
                $authorization->save();

                if ($authorization->companion) {
                    AuthorizationCompanion::updateRecord($authorization->id, $request);
                }
                AuthorizationPrice::updateRecord($authorization, $request);
                AuthorizationDate::updateRecord($authorization, $request);
                AuthorizationService::updateRecord($authorization, $request);
                Patient::createOrUpdatePhone($authorization->patient_id, $request);

                $invoice = Invoice::getInvoiceByAuthorizationCode($authorization->code);
                if ($invoice) {
                    $invoice->update([
                        'total' => $authorization->eps->daily_price * $authorization->days
                    ]);
                }

                DB::commit();
            } catch (\Exception $e) {
                DB::rollback();
                dd($e);
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
            ->orderBy('created_at', 'desc')
            ->get();
    }

    protected function open()
    {
        return $this->where('invoice_id', 0)
            ->orderBy('created_at', 'desc')
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
            ->whereNull('deleted_at')
            ->first();
    }

    protected function global($search = '')
    {
        return $this::where('authorizations.code', 'not like', config('constants.unathorized.prefix').'%')->where('authorizations.code', 'like', '%'.$search.'%')
            ->paginate(config('constants.pagination'));
    }

    protected function matchAuthorizationsWithInvoices()
    {
        $invoices = Invoice::where('multiple', 1)
            ->whereNull('deleted_at')
            ->where('created_at', '>', '2019-01-01 00:00:00')
            ->get();

        echo "\nFound: ".count($invoices)." invoices multiple";
        
        $counter = 0;
        $authorizationsCounter = 0;
        foreach ($invoices as $invoice) {
            $flag = false;
            foreach (json_decode($invoice->multiple_codes, true) as $key => $code) {
                $authorization = $this->findByCode($code);
                if ($authorization and $authorization->days != json_decode($invoice->multiple_days, true)[$key]) {
                    $authorizationsCounter++;
                    $flag = true;
                    $authorization->date_to = \Carbon\Carbon::parse($authorization->date_from)
                         ->addDays(json_decode($invoice->multiple_days, true)[$key])->format("Y-m-d");
                    $authorization->save();

                    AuthorizationService::fixRecord($authorization, floatval(json_decode($invoice->multiple_days, true)[$key]));
                    // echo ' -> Fixed on authorization: '.$authorization->code."\n";                    
                }                
            }
            if ($flag) {
                $counter++;
                echo "\nInvoice to fix: $invoice->number";
            }
        }
        echo "\n$counter invoices don't match with authorizations";
        echo "\n$authorizationsCounter authorizations to fix";
    }
    

    protected function matchAuthorizationsWithInvoice($invoiceNumber)
    {
        $invoice = Invoice::getInvoiceByNumber($invoiceNumber);
        if (!$invoice) {
            return false;
        }

        echo 'Invoice number: '.$invoice->number;

        if ($invoice->multiple) {
            foreach (json_decode($invoice->multiple_codes, true) as $key => $code) {
                $authorization = $this->findByCode($code);
                if ($authorization) {
                    $authorization->date_to = \Carbon\Carbon::parse($authorization->date_from)
                        ->addDays(json_decode($invoice->multiple_days, true)[$key])->format("Y-m-d");
                    $authorization->save();

                    AuthorizationService::fixRecord($authorization, floatval(json_decode($invoice->multiple_days, true)[$key]));
                    echo ' -> Fixed on authorization: '.$authorization->code."\n";                    
                }                
            }
            return true;
        }
        echo " -> Left the same\n";
        return true;
    }

    protected function createAuthorizationService($authorizationCode)
    {
        $authorization = $this->findByCode($authorizationCode);
        if (!$authorization) {
            return false;
        }

        $request = new Request();
        $request->request->add([
            'eps_service_id' => $authorization->eps_service_id,
            'daily_price' => $authorization->service->price,
            'total_days' => $authorization->days
        ]);

        AuthorizationService::updateRecord($authorization, $request);
    }

    protected function checkAuthorizationService($max = 30)
    {
        $authorizations = $this::where('multiple', 0)
            ->get();

        $counter = 0;
        foreach ($authorizations as $key => $authorization) {
            $authorizationService = AuthorizationService::checkIfExists($authorization);
            if (!$authorizationService) {
                $counter++;
                $this->createAuthorizationService($authorization->code);
                echo "\nAuthorization code: $authorization->code";
            }

            if ($counter == $max) {
                break;
            }
        }
        echo "\n\nAuthorizations that don't exist on AuthorizationService: $counter";
    }


    protected function createAuthorizationPrice($authorizationCode)
    {
        $authorization = $this->findByCode($authorizationCode);
        if (!$authorization) {
            return false;
        }

        $request = new Request();
        $request->request->add([
            'eps_id' => $authorization->eps_id,
            'daily_price' => $authorization->service->price,
        ]);

        AuthorizationPrice::updateRecord($authorization, $request);
    }
}
