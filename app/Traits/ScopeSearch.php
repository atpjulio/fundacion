<?php

namespace App\Traits;

trait ScopeSearch
{
	public function scopeSearch($query, $fields, $search = '')
	{
    $query->when(empty($search) ? null : $search, function ($query, $search) use ($fields) {
      $search = "%$search%";
      collect($fields)->each(function ($field) use ($query, $search) {
        $query->where($field, 'like', $search);
      });
    });
	}
}