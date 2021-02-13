<?php

namespace App\Models\Invoices;

use App\Models\Eps\Eps;
use App\Models\Merchants\Merchant;
use App\Models\Patients\Patient;
use App\Traits\ModelResults;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
  use ModelResults;

  protected $appends  = ['amount'];
  protected $fillable = [
    'merchant_id',
    'eps_id',
    'serie_id',
    'patient_id',
    'number',
    'code',
    'date',
    'expiration_date',
    'status', // CREATED, SENT, PAID, RETURN
    'dni_type',
    'dni',
    'first_name',
    'last_name',
  ];
  private $sortField = 'code';

  /**
   * Attributes
   */

  public function getAmountAttribute()
  {
    return $this->items()->sum('amount');
  }

  /**
   * Relationships
   */

  public function eps()
  {
    return $this->belongsTo(Eps::class);
  }

  public function items()
  {
    return $this->hasMany(InvoiceItem::class);
  }

  public function merchant()
  {
    return $this->belongsTo(Merchant::class);
  }

  public function patient()
  {
    return $this->belongsTo(Patient::class);
  }

  public function serie()
  {
    return $this->belongsTo(InvoiceSerie::class);
  }

  /**
   * Scopes
   */

  public function scopeOption($query, $request)
  {
    $option = $request->get('option');
    $query->when($option > 0 ? $option : null, function ($query, $option) {
      $query->where('eps_id', $option);
    });
  }

  public function scopeSearch($query, $request)
  {
    $query->when($request->get('search'), function ($query, $search) {
      $query->where('code', 'like', "%$search%");
    });
  }

  public function scopeSort($query, $request)
  {
    $query->when($request->get('sortDirection'), function ($query, $sortDirection) {
      $query->orderBy($this->sortField, $sortDirection);
    });
  }

  /**
   * Methods
   */

  protected function getLastNumber($merchantId, $serieId)
  {
    return $this->where('merchant_id', $merchantId)
      ->where('serie_id', $serieId)
      ->max('number');
  }

  protected function getCodeFrom($serie)
  {
    return $serie->prefix . $serie->number;
  }

  protected function getLatestRecords(Request $request)
  {
    $query = $this->with('eps:id,name')
      ->option($request)
      ->search($request)
      ->sort($request);

    return $this->paginateResult($request, $query);
  }

  protected function deleteRecord($id)
  {
    $this->where('id', $id)
      ->delete();
  }
}
