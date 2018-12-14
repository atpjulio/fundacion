<div class="form-group  @if($errors->has('code')) has-error @endif">
    {!! Form::label('code', 'Código', ['class' => 'control-label']) !!}
    {!! Form::text('code', old('code', isset($eps) ? $eps->code : ''), ['class' => 'form-control underlined', 'placeholder' => 'Código de la EPS']) !!}
</div>
<div class="form-group  @if($errors->has('name')) has-error @endif">
    {!! Form::label('name', 'Nombre', ['class' => 'control-label']) !!}
    {!! Form::text('name', old('name', isset($eps) ? $eps->name : ''), ['class' => 'form-control underlined', 'placeholder' => 'Nombre de la EPS']) !!}
</div>
<div class="form-group  @if($errors->has('nit')) has-error @endif">
    {!! Form::label('nit', 'NIT', ['class' => 'control-label']) !!}
    {!! Form::text('nit', old('nit', isset($eps) ? $eps->nit : ''), ['class' => 'form-control underlined', 'placeholder' => 'NIT']) !!}
</div>
<div class="form-group  @if($errors->has('alias')) has-error @endif">
    {!! Form::label('alias', 'Nombre corto', ['class' => 'control-label']) !!}
    {!! Form::text('alias', old('alias', isset($eps) ? $eps->alias : ''), ['class' => 'form-control underlined', 'placeholder' => 'Nombre corto para usarlo en el sistema']) !!}
</div>
<div class="form-group  @if($errors->has('contract')) has-error @endif">
    {!! Form::label('contract', 'Número de contrato', ['class' => 'control-label']) !!}
    {!! Form::text('contract', old('contract', isset($eps) ? $eps->contract : ''), ['class' => 'form-control underlined', 'placeholder' => 'Número de Contrato']) !!}
</div>
<div class="form-group  @if($errors->has('policy')) has-error @endif">
    {!! Form::label('policy', 'Póliza', ['class' => 'control-label']) !!}
    {!! Form::text('policy', old('policy', isset($eps) ? $eps->policy : ''), ['class' => 'form-control underlined', 'placeholder' => 'Póliza']) !!}
</div>
