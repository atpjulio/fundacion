<div class="form-group @if ($errors->has('code')) has-error @endif">
  <label for="code" class="control-label">Código</label>
  {!! Form::text('code', old('code', isset($service) ? $service->code : ''), ['class' => 'form-control underlined',
  'placeholder' => 'Código del servicio', 'id' => 'code-group', 'focus']) !!}
</div>
<div class="form-group @if ($errors->has('name')) has-error @endif">
  <label for="name" class="control-label">Nombre</label>
  {!! Form::text('name', old('name', isset($service) ? $service->name : ''), ['class' => 'form-control underlined',
  'placeholder' => 'Nombre del servicio', 'maxlength' => 190, 'id' => 'name-group']) !!}
</div>
<div class="form-group @if ($errors->has('price')) has-error @endif">
  <label for="price" class="control-label">Precio</label>
  {!! Form::number('price', old('price', (isset($service) and $service->price > 0) ? $service->price :
  ($eps->daily_price > 0 ? $eps->daily_price : $eps->price[0]->daily_price)), ['class' => 'form-control underlined',
  'placeholder' => 'Precio del servicio (opcional)', 'min' => 1, 'id' => 'price-group']) !!}
</div>
