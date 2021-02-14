@if (!isset($show))
  <div class="form-group  @if ($errors->has('eps_id')) has-error @endif">
    <label for="eps_id" class="control-label">EPS a la que pertenece el usuario</label>
    {!! Form::select('eps_id', $epss, old('eps_id', isset($patient) ? $patient->eps_id : ''), ['class' =>
    'form-control']) !!}
  </div>
@endif
<div class="form-group  @if ($errors->has('dni_type')) has-error @endif">
  <label for="dni_type" class="control-label">Tipo de Documento</label>
  {!! Form::select('dni_type', config('constants.documentTypes'), old('dni_type', isset($patient) ? $patient->dni_type :
  ''), ['class' => 'form-control', isset($show) ? 'disabled' : '']) !!}
</div>
<div class="form-group @if ($errors->has('dni')) has-error @endif">
  <label for="dni" class="control-label">Número de documento</label>
  <input type="text" name="dni" class="form-control underlined" id="dni" @isset($show) readonly @endisset
    value="{{ old('dni', isset($patient) ? $patient->dni : '') }}" placeholder="Número de documento">
  @if (isset($patient))
    <input type="hidden" name="old_dni" value="{{ $patient->dni }}" id="old_dni">
  @endif
  <span class="text-danger" role="alert" id="check-patient"><em></em></span>
</div>
<div class="form-group @if ($errors->has('last_name')) has-error @endif">
  <label for="last_name" class="control-label">Apellido(s)</label>
  <input type="text" name="last_name" class="form-control underlined" @isset($show) readonly @endisset
    value="{{ old('last_name', isset($patient) ? $patient->last_name : '') }}" placeholder="Apellido(s)">
</div>
<div class="form-group @if ($errors->has('first_name')) has-error @endif">
  <label for="first_name" class="control-label">Nombre(s)</label>
  <input type="text" name="first_name" class="form-control underlined" @isset($show) readonly @endisset
    value="{{ old('first_name', isset($patient) ? $patient->first_name : '') }}" placeholder="Nombre(s)">
</div>
<div class="form-group @if ($errors->has('birth_date')) has-error @endif">
  <label for="birth_date" class="control-label">Fecha de Nacimiento</label>
</div>
<div class="form-inline">
  <div class="form-group  @if ($errors->has('birth_date')) has-error @endif">
    {!! Form::selectRange('birth_year', 1918, date('Y'), isset($patient) ? substr($patient->birth_date, 0, 4) : 1970,
    ['class' => 'form-control', 'id' => 'birth_year', isset($show) ? 'disabled' : '']) !!}
    &nbsp;&nbsp;
    {!! Form::select('birth_month', config('constants.months'), isset($patient) ? intval(substr($patient->birth_date, 5,
    2)) : 1, ['class' => 'form-control', 'id' => 'birth_month', isset($show) ? 'disabled' : '']) !!}
    &nbsp;&nbsp;
    <div id="dynamic-days">
      {!! Form::selectRange('birth_day', 1, 28, isset($patient) ? intval(substr($patient->birth_date, 8, 2)) : 1,
      ['class' => 'form-control', 'id' => 'birth_day', isset($show) ? 'disabled' : '']) !!}
    </div>
  </div>
</div>
<br>
