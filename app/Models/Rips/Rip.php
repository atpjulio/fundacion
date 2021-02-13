<?php

namespace App\Models\Rips;

use App\Models\Eps\Eps;
use App\Models\Invoices\Invoice;
use App\Models\Merchants\Merchant;
use App\Models\Patients\PatientData;
use App\Utils\Utilities;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class Rip extends Model
{
  protected $fillable = [
    'merchant_id',
    'eps_id',
    'filename',
    'date',
    'status', // CREATED, SENT, PAID, RETURN
  ];

  /**
   * Attributes
   */

  /**
   * Relationships
   */

  public function merchant()
  {
    return $this->belongsTo(Merchant::class);
  }

  public function eps()
  {
    return $this->belongsTo(Eps::class);
  }

  public function afs()
  {
    return $this->hasMany(RipAfRecord::class);
  }

  public function ats()
  {
    return $this->hasMany(RipAtRecord::class);
  }

  public function cts()
  {
    return $this->hasMany(RipCtRecord::class);
  }

  public function uss()
  {
    return $this->hasMany(RipUsRecord::class);
  }

  /**
   * Scopes
   */

  /**
   * Methods
   */

  public function buildRecords(Collection $invoiceIds)
  {
    if ($invoiceIds->isEmpty()) {
      return;
    }

    $merchant = $this->merchant;
    $eps = $this->eps;

    Invoice::with(['patient', 'items'])
      ->where('merchant_id', $this->merchant_id)
      ->where('eps_id', $this->eps_id)
      ->whereIn('id', $invoiceIds)
      ->orderBy('number')
      ->chunk(500, function ($invoices) use ($merchant, $eps) {
        foreach ($invoices as $invoice) {
          $this->buildAfRecord($merchant, $invoice, $eps);
          $this->buildAtRecords($merchant, $invoice);
          $this->buildUsRecords($eps, $invoice->patient);
        }
      });

    $this->buildCtRecords($merchant);
  }

  public function buildAfRecord($merchant, $invoice, $eps)
  {
    $this->afs()->create([
      'merchant_dni_with_zeros' => $merchant->dni_with_zeros,
      'merchant_name'           => $merchant->name,
      'merchant_dni_type'       => $merchant->dni_type,
      'merchant_dni_short'      => $merchant->dni_short,
      'invoice_number'          => $invoice->number,
      'date'                    => $this->date,
      'initial_date'            => $this->date,
      'final_date'              => $this->date,
      'eps_code'                => $eps->code,
      'eps_name'                => $eps->name,
      'eps_contract_number'     => $eps->contract_number,
      'extra1'                  => '',
      'extra2'                  => '',
      'extra3'                  => 0,
      'extra4'                  => 0,
      'extra5'                  => 0,
      'amount'                  => $invoice->amount,
    ]);
  }

  public function buildAtRecords($merchant, $invoice)
  {
    $ats = collect();
    foreach ($invoice->items as $item) {
      $at = new RipAtRecord([
        'invoice_number'          => $invoice->number,
        'merchant_dni_with_zeros' => $merchant->dni_with_zeros,
        'patient_dni_type'        => $invoice->dni_type,
        'patient_dni'             => $invoice->dni,
        'authorization_code'      => $item->authorization_code,
        'service_code'            => $item->service_code,
        'extra1'                  => '1',
        'service_name'            => $item->service_name,
        'quantity'                => $item->quantity,
        'amount'                  => $item->amount,
      ]);
      $ats->push($at);
    }
    $this->ats()->saveMany($ats);
  }

  public function buildCtRecords($merchant)
  {
    $cts = collect();

    $ctAf = new RipCtRecord([
      'merchant_dni_with_zeros' => $merchant->dni_with_zeros,
      'date'                    => $this->date,
      'prefix'                  => 'AF',
      'filename'                => $this->filename,
      'count'                   => $this->afs()->count(),
    ]);

    $cts->push($ctAf);

    $ctAt = $ctAf->replicate();

    $ctAt->prefix = 'AT';
    $ctAt->count  = $this->ats()->count();

    $cts->push($ctAt);

    $ctUs = $ctAf->replicate();

    $ctUs->prefix = 'US';
    $ctUs->count  = $this->uss()->count();

    $cts->push($ctUs);

    $this->cts()->saveMany($cts);
  }

  public function buildUsRecords($eps, $patient)
  {
    $data = PatientData::with(['city', 'state'])
      ->where('patient_id', $patient->id)
      ->first();

    $this->uss()->create([
      'patient_dni_type' => $patient->dni_type,
      'patient_dni'      => $patient->dni,
      'eps_code'         => $eps->code,
      'patient_type'     => $data->type,
      'last_name'        => $patient->last_name,
      'first_name'       => $patient->first_name,
      'age_type'         => Utilities::getAgeTypeForRip($data),
      'gender'           => $data->gender,
      'state'            => $data->state->code,
      'city'             => $data->city->code,
      'patient_zone'     => $data->zone,
    ]);
  }
}
