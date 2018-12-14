<div class="form-group @if($errors->has('code')) has-error @endif">
    {!! Form::label('code', 'Código', ['class' => 'control-label']) !!}
    {!! Form::text('code', old('code', isset($service) ? $service->code : ''), ['class' => 'form-control underlined', 'placeholder' => 'Código del servicio']) !!}
</div>
<div class="form-group @if($errors->has('name')) has-error @endif">
    {!! Form::label('name', 'Nombre', ['class' => 'control-label']) !!}
    {!! Form::text('name', old('name', isset($service) ? $service->name : ''), ['class' => 'form-control underlined', 'placeholder' => 'Nombre del servicio', 'maxlength' => 190]) !!}
</div>
<div class="form-group @if($errors->has('price')) has-error @endif">
    {!! Form::label('price', 'Precio', ['class' => 'control-label']) !!}
    {!! Form::text('price', old('price', $eps->price ? $eps->price[0]->daily_price : $eps->daily_price), ['class' => 'form-control underlined', 'placeholder' => 'Precio del servicio (opcional)', 'min' => 1]) !!}
</div>
