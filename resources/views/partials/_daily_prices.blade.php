@php
  $currentEps = \App\Eps::find(old('eps_id') ?: $initialEpsId);
@endphp

@if (isset($authorization))
  {!! Form::number('daily_price', old('daily_price', $authorization->daily_price), ['class' => 'form-control', 'readonly', 'id' => 'daily_price']) !!}
@elseif (isset($currentEps) and count($currentEps->price) > 0)
  {!! Form::number('daily_price', old('daily_price', $currentEps->price[0]->daily_price), ['class' => 'form-control', 'readonly', 'id' => 'daily_price']) !!}
@elseif(isset($currentEps))
  {!! Form::number('daily_price', old('daily_price', $currentEps->daily_price), ['class' => 'form-control', 'readonly', 'id' => 'daily_price']) !!}
@else
  {!! Form::number('daily_price', old('daily_price', 0), ['class' => 'form-control', 'readonly', 'id' => 'daily_price']) !!}
@endif
{{-- 
<select class="form-control" name="daily_price">
  @if (isset($currentEps) and count($currentEps->price) > 0)
    @foreach ($currentEps->price as $epsPrice)
      <option value="{{ $epsPrice->daily_price }}" 
        @if (isset($authorization) and $authorization->price and $authorization->price->daily_price == $epsPrice->daily_price) selected @endif>
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
--}}
