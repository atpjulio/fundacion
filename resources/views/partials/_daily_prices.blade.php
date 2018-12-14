@php
  $currentEps = \App\Eps::find(old('eps_id') ?: $initialEpsId);
@endphp

<select class="form-control" name="daily_price">
  @if (isset($currentEps) and count($currentEps->price) > 0)
    @foreach ($currentEps->price as $epsPrice)
      <option value="{{ $epsPrice->daily_price }}">
        {!! number_format($epsPrice->daily_price, 2, ",", ".").' - '.$epsPrice->name !!}
      </option>
    @endforeach
  @elseif(isset($currentEps))
    <option value="{{ $currentEps->daily_price }}">
      {!! number_format($currentEps->daily_price, 2, ",", ".").' - ' !!}
      Precio actual
    </option>
  @else
    <option value="">Sin tarifa que mostrar</option>
  @endif
</select>
