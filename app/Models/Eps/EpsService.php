<?php

namespace App\Models\Eps;

use App\Traits\Authorizable;
use App\Traits\ModelResults;
use App\Traits\ScopeActive;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use stdClass;

class EpsService extends Model
{
  use Authorizable, ScopeActive, ModelResults;

  protected $table    = 'new_eps_services';
  protected $fillable = [
    'eps_id',
    'code',
    'name',
    'amount',
    'status',
  ];
  private $sortField = 'name';

  /**
   * Attributes
   */

  /**
   * Relationships
   */

  public function eps()
  {
    return $this->belongsTo(Eps::class);
  }

  /**
   * Scopes
   */

  public function scopeOption($query, $request)
  {
    $option = $request->get('option');
    $query->when($option, function ($query, $option) {
      $query->where('status', $option);
    });
  }

  public function scopeSearch($query, $request)
  {
    $query->when($request->get('search'), function ($query, $search) {
      $query->where('name', 'like', "%$search%");
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

  protected function getLatestRecords(Request $request, $epsId)
  {
    $query = $this->where('eps_id', $epsId)
      ->option($request)
      ->search($request)
      ->sort($request);

    return $this->paginateResult($request, $query);
  }

  protected function storeRecord(Request $request, $epsId)
  {
    $service = new EpsService($request->all());

    $service->eps_id = $epsId;
    $service->status = $request->get('status') ?? config('enum.status.active');

    $service->save();

    return $service;
  }

  protected function getRecord($id)
  {
    return $this->findOrFail($id);
  }

  protected function updateRecord(Request $request, $id)
  {
    $service = $this->find($id);

    $service->update($request->all());

    return $service;
  }

  protected function deleteRecord($id)
  {
    $this->where('id', $id)
      ->delete();
  }

  protected function updateStatus($id)
  {
    $service = $this->findOrFail($id);

    if ($service->status == config('enum.status.active')) {
      $service->status = config('enum.status.inactive');
    } else {
      $service->status = config('enum.status.active');
    }
    $service->save();

    return $service;
  }

  protected function getForSelect($whereHas = null, $defaultText = 'Todos los servicios', $withPrepend = true)
  {
    $defaultOption = new stdClass();

    $defaultOption->value = 0;
    $defaultOption->name = $defaultText;

    $query = $this->select('id as value', DB::raw("CONCAT(code, ' - ', name) as name"));

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

  protected function getForAjax(Request $request)
  {
    $filters = $request->get('filters') ? json_decode($request->get('filters'), true) : [];
    $limit   = $request->get('limit') ?: config('constants.limit');

    $query = $this->select('id', 'code', 'name', 'amount');

    collect($filters)->each(function ($value, $key) use ($query) {
      if ($value !== '') {
        if ($key == 'code' or $key == 'name') {
          $value = "$value%";
          $query->where($key, 'like', $value);
        } else {
          $query->where($key, $value);
        }
      }
    });

    return $query->take($limit)
      ->sort($request)
      ->get()
      ->toArray();
  }
}
