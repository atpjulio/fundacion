<?php

namespace App\Traits;

trait ScopeSort
{
  public function scopeSort($query, $request)
  {
    $query->when($request->get('sortDirection'), function ($query, $sortDirection) {
      $query->orderBy($this->sortField, $sortDirection);
    });
  }
}