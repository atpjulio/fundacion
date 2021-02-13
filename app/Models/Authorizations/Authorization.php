<?php

namespace App\Models\Authorizations;

use App\Models\Eps\Eps;
use App\Models\Eps\EpsService;
use App\Models\Merchants\Merchant;
use App\Models\Patients\Companion;
use App\Models\Patients\Patient;
use App\Traits\ModelResults;
use App\Traits\ScopeSort;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class Authorization extends Model
{
  use ModelResults, ScopeSort;

  protected $fillable = [
    'merchant_id',
    'eps_id',
    'patient_id',
    'code',
    'date',
    'location',
    'diagnosis',
  ];
  private $sortField = 'code';

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

  public function patient()
  {
    return $this->belongsTo(Patient::class);
  }

  public function items()
  {
    return $this->hasMany(AuthorizationItem::class);
  }

  public function services()
  {
    return $this->items()->where('authorizable_type', EpsService::class);
  }

  public function companions()
  {
    return $this->items()->where('authorizable_type', Companion::class);
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
      $query->where('name', 'like', "%$search%");
    });
  }

  /**
   * Methods
   */

  protected function getLatestRecords(Request $request)
  {
    $query = $this->with(['eps:id,name', 'patient:id,first_name,last_name', 'services.authorizable:id,amount,code'])
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
