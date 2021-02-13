<?php

namespace App\Traits;

trait ScopeActive
{
	public function scopeActive($query)
	{
		$query->where('status', config('constants.status.active'));
	}
}