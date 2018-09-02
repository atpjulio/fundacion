<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\SoftDeletes;

class Rip extends Model
{
	use SoftDeletes;

	protected $fillable = [
        'company_id',
        'eps_id',
        'initial_date',
        'final_date',
        'created_at'
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
    
    /**
     * Methods
     */
    protected function produceRIPS($request)
    {
        $invoices = Invoice::getInvoicesByEpsId($request->get('eps_id'));

        $rip = null;

        if (count($invoices) <= 0) {
            return $rip;
        }

        $line = "";

        foreach ($invoices as $invoice) {
            $days = \Carbon\Carbon::parse($invoice->authorization->date_to)->diffInDays(\Carbon\Carbon::parse($invoice->authorization->date_from));
            $line .= $invoice->number.",".substr($invoice->company->nit, 0, 9).","
                .$invoice->authorization->patient->dni_type.",".$invoice->authorization->patient->dni.","
                .$invoice->authorization->code.",1,".$invoice->authorization->service->code.","
                .strtoupper($invoice->authorization->service->name).","
                .$days.",".$invoice->eps->daily_price.","
                .floatval($days * $invoice->eps->daily_price)."\r";
        }

        $lastRip = $this->all()
            ->last();

        $fileName = "AT".sprintf("%06d", $lastRip ? $lastRip->id + 1 : 1).".TXT";

        Storage::put(config('constants.ripsFiles').$fileName, $line);

        $rip = new Rip();

        $rip->company_id = $request->get('company_id');
        $rip->eps_id = $request->get('eps_id');
        $rip->initial_date = $request->get('initial_date');
        $rip->final_date = $request->get('final_date');
        $rip->created_at = $request->get('created_at');
        //$rip->url = //Storage::url(config('constants.ripsFiles').$fileName);
        $rip->url = config('constants.ripsFiles').$fileName;

        $rip->save();

        return $rip;
    }

    protected function updateRIPS($request, $id)
    {
        $invoices = Invoice::getInvoicesByEpsId($request->get('eps_id'));
        $rip = $this->find($id);

        if (count($invoices) <= 0 or !$rip) {
            return null;
        }

        $line = "";

        foreach ($invoices as $invoice) {
            $days = \Carbon\Carbon::parse($invoice->authorization->date_to)->diffInDays(\Carbon\Carbon::parse($invoice->authorization->date_from));
            $line .= $invoice->number.",".substr($invoice->company->nit, 0, 9).","
                .$invoice->authorization->patient->dni_type.",".$invoice->authorization->patient->dni.","
                .$invoice->authorization->code.",1,".$invoice->authorization->service->code.","
                .strtoupper($invoice->authorization->service->name).","
                .$days.",".$invoice->eps->daily_price.","
                .floatval($days * $invoice->eps->daily_price)."\r";
        }

        $fileName = "AT".sprintf("%06d", $rip->id).".TXT";

        Storage::delete(config('constants.ripsFiles').$fileName);
        Storage::put(config('constants.ripsFiles').$fileName, $line);

        $rip->company_id = $request->get('company_id');
        $rip->eps_id = $request->get('eps_id');
        $rip->initial_date = $request->get('initial_date');
        $rip->final_date = $request->get('final_date');
        $rip->created_at = $request->get('created_at');
        $rip->url = config('constants.ripsFiles').$fileName;

        $rip->save();

        return $rip;
    }


}
