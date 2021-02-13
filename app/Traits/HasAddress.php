<?php

namespace App\Traits;

use Illuminate\Http\Request;

trait HasAddress
{
  public function storeOrUpdateAddress(Request $request)
  {
    $dataForAddress = $request->only([
      'city_id',
      'state_id',
      'line1',
      'line2',
      'country'
    ]);

    $dataForAddress['country'] = $dataForAddress['country'] ?? config('constants.default.country');

    $address = $this->address;
    if (!$address) {
      return $this->address()->create($dataForAddress);
    }
    $address->update($dataForAddress);
    return $address;
  }
}
