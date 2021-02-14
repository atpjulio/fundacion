<div class="form-group  @if ($errors->has('code')) has-error @endif">
  <label for="code" class="control-label">Código</label>
  {!! Form::text('code', old('code', isset($eps) ? $eps->code : ''), ['class' => 'form-control underlined',
  'placeholder' => 'Código de la EPS']) !!}
</div>
<div class="form-group  @if ($errors->has('name')) has-error @endif">
  <label for="name" class="control-label">Nombre</label>
  {!! Form::text('name', old('name', isset($eps) ? $eps->name : ''), ['class' => 'form-control underlined',
  'placeholder' => 'Nombre de la EPS']) !!}
</div>
<div class="form-group  @if ($errors->has('nit')) has-error @endif">
  <label for="nit" class="control-label">NIT</label>
  {!! Form::text('nit', old('nit', isset($eps) ? $eps->nit : ''), ['class' => 'form-control underlined', 'placeholder'
  => 'NIT']) !!}
</div>
<div class="form-group  @if ($errors->has('alias')) has-error @endif">
  <label for="alias" class="control-label">Nombre corto</label>
  {!! Form::text('alias', old('alias', isset($eps) ? $eps->alias : ''), ['class' => 'form-control underlined',
  'placeholder' => 'Nombre corto para usarlo en el sistema']) !!}
</div>
<div class="form-group  @if ($errors->has('contract')) has-error @endif">
  <label for="contract" class="control-label">Número de contrato</label>
  {!! Form::text('contract', old('contract', isset($eps) ? $eps->contract : ''), ['class' => 'form-control underlined',
  'placeholder' => 'Número de Contrato']) !!}
</div>
<div class="form-group  @if ($errors->has('policy')) has-error @endif">
  <label for="policy" class="control-label">Póliza</label>
  {!! Form::text('policy', old('policy', isset($eps) ? $eps->policy : ''), ['class' => 'form-control underlined',
  'placeholder' => 'Póliza']) !!}
</div>
