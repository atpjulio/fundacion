<?php

namespace App\Traits;

trait ScopeSort
{
  public function scopeSort($query, $request)
  {
    $query->orderBy($this->sortField, strtolower($request->get('sortDirection')) == 'asc' ? 'asc' : 'desc');
  }
}