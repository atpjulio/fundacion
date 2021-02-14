@php
$currentEps = \App\Eps::find(old('eps_id') ?: $initialEpsId);
@endphp

@if (isset($authorization))
  <input type="number" name="daily_price" id="daily_price" readonly class="form-control"
    value="{{ old('daily_price', $authorization->daily_price_single) }}">
@elseif (isset($currentEps) and count($currentEps->price) > 0)
  <input type="number" name="daily_price" id="daily_price" readonly class="form-control"
    value="{{ old('daily_price', $currentEps->price[0]->daily_price) }}">
@elseif(isset($currentEps))
  <input type="number" name="daily_price" id="daily_price" readonly class="form-control"
    value="{{ old('daily_price', $currentEps->daily_price) }}">
@else
  <input type="number" name="daily_price" id="daily_price" readonly class="form-control"
    value="{{ old('daily_price', 0) }}">
@endif
