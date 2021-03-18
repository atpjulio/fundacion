<?php

namespace App\Models\Eps;

use App\Models\Authorizations\Authorization;
use App\Models\Merchants\Merchant;
use App\Models\Patients\Companion;
use App\Models\Patients\Patient;
use App\Traits\HasAddress;
use App\Traits\ModelResults;
use App\Traits\ScopeSort;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use stdClass;

class Eps extends Model
{
  use HasAddress, ModelResults, ScopeSort;

  protected $table    = 'new_eps';
  protected $fillable = [
    'merchant_id',
    'code',
    'color',
    'name',
    'nit',
    'alias',
    'contract_number',
    'policy',
    'phone1',
    'phone2',
  ];
  private $sortField = 'name';

  /**
   * Attributes
   */

  /**
   * Relationships
   */

  public function address()
  {
    return $this->hasOne(EpsAddress::class, 'parent_id');
  }

  public function authorizations()
  {
    return $this->hasMany(Authorization::class);
  }

  public function companions()
  {
    return $this->hasMany(Companion::class);
  }

  public function merchant()
  {
    return $this->belongsTo(Merchant::class);
  }

  public function patients()
  {
    return $this->hasMany(Patient::class);
  }

  public function services()
  {
    return $this->hasMany(EpsService::class);
  }

  /**
   * Scopes
   */

  public function scopeOption($query, $request)
  {
    $option = $request->get('option');
    $query->when($option > 0 ? $option : null, function ($query, $option) {
      $query->where('merchant_id', $option);
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
    $query = $this->with('merchant:id,name')
      ->option($request)
      ->search($request)
      ->sort($request);

    return $this->paginateResult($request, $query);
  }

  protected function storeRecord($request)
  {
    $eps = new Eps($request->all());

    $eps->color = sprintf('#%06X', mt_rand(0, 0xFFFFFF));

    $eps->save();
    $eps->storeOrUpdateAddress($request);

    return $eps;
  }

  protected function getRecord($id)
  {
    return $this->with(['address'])
      ->where('id', $id)
      ->firstOrFail();
  }

  protected function updateRecord($request, $id)
  {
    $eps = $this->find($id);

    $eps->update($request->all());
    $eps->storeOrUpdateAddress($request);

    return $eps;
  }

  protected function deleteRecord($id)
  {
    $this->where('id', $id)
      ->delete();
  }

  protected function getForSelect($whereHas = null, $defaultText = 'Todas las EPS', $withPrepend = true)
  {
    $defaultOption = new stdClass();

    $defaultOption->value = 0;
    $defaultOption->name = $defaultText;

    $query = $this->select('id as value', 'name');

    if ($whereHas) {
      $query->whereHas($whereHas);
    }

    $options = $query->orderBy($this->sortField)
      ->get();

    if ($withPrepend) {
      return $options->prepend($defaultOption);
    }
    return $options;
  }
}
