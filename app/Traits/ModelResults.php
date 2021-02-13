<?php

namespace App\Traits;

use App\Utils\Utilities;

trait ModelResults
{
  private function paginateResult($request, $query)
  {
    $records = $query->paginate($request->get('pagination') ?? config('constants.pagination'));

    $request->merge(['links' => Utilities::buildLinks($records)]);

    return $records->items();
  }

  private function buildResult($request, $query)
  {
    $last = $request->get('last') ?: 0;
    if ($last > 0) {
      $query = $query->take($last);
    }

    return $query->get()->toArray();
  }
}
