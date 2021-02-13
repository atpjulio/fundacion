<?php

namespace App\Models\Patients;

use App\Models\Eps\Eps;
use App\Models\Merchants\Merchant;
use App\Models\Shared\Participant;
use App\Traits\Authorizable;
use App\Traits\ModelResults;
use App\Traits\ScopeSort;
use Illuminate\Http\Request;

class Companion extends Participant
{
  use Authorizable, ModelResults, ScopeSort;

  protected $table = 'companions';
  private $sortField = 'id';

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
      $query->where('dni', 'like', "%$search%");
    });
  }

  /**
   * Methods
   */

  protected function getLatestRecords(Request $request)
  {
    $query = $this->with(['eps:id,name'])
      ->option($request)
      ->search($request)
      ->sort($request);

    return $this->paginateResult($request, $query);
  }

  protected function storeRecord(Request $request)
  {
    $companion = new Companion($request->all());

    $companion->save();

    return $companion;
  }

  protected function getRecord($id)
  {
    return $this->where('id', $id)
      ->firstOrFail();
  }

  protected function updateRecord(Request $request, $id)
  {
    $companion = $this->find($id);

    $companion->update($request->all());

    return $companion;
  }

  protected function deleteRecord($id)
  {
    $this->where('id', $id)
      ->delete();
  }

  protected function getForAjax(Request $request)
  {
    $filters = $request->get('filters') ? json_decode($request->get('filters'), true) : [];
    $limit   = $request->get('limit') ?: config('constants.limit');

    $query = $this->select('id', 'dni_type', 'dni', 'first_name', 'last_name');

    collect($filters)->each(function ($value, $key) use ($query) {
      if ($value !== '') {
        if ($key == 'dni') {
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
