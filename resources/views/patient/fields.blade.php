@if (!isset($show))
<div class="form-group  @if($errors->has('eps_id')) has-error @endif">
    {!! Form::label('eps_id', 'EPS a la que pertenece el usuario', ['class' => 'control-label']) !!}
    {!! Form::select('eps_id', $epss, old('eps_id', isset($patient) ? $patient->eps_id : ''), ['class' =>
    'form-control']) !!}
</div>
@endif
<div class="form-group  @if($errors->has('dni_type')) has-error @endif">
    {!! Form::label('dni_type', 'Tipo de Documento', ['class' => 'control-label']) !!}
    {!! Form::select('dni_type', config('constants.documentTypes'), old('dni_type', isset($patient) ? $patient->dni_type
    : ''), ['class' => 'form-control', isset($show) ? 'disabled' : '']) !!}
</div>
<div class="form-group @if($errors->has('dni')) has-error @endif">
    {!! Form::label('dni', 'Número de documento', ['class' => 'control-label']) !!}
    {!! Form::text('dni', old('dni', isset($patient) ? $patient->dni : ''), ['class' => 'form-control underlined',
    'placeholder' => 'Número de documento', isset($show) ? 'readonly' : '', 'id' => 'dni']) !!}
    @if (isset($patient))
    {!! Form::hidden('old_dni', $patient->dni, ['id' => 'old_dni']) !!}
    @endif
    <span class="text-danger" role="alert" id="check-patient"><em></em></span>
</div>
<div class="form-group @if($errors->has('last_name')) has-error @endif">
    {!! Form::label('last_name', 'Apellido(s)', ['class' => 'control-label']) !!}
    {!! Form::text('last_name', old('last_name', isset($patient) ? $patient->last_name : ''), ['class' => 'form-control
    underlined', 'placeholder' => 'Apellido(s)', isset($show) ? 'readonly' : '']) !!}
</div>
<div class="form-group @if($errors->has('first_name')) has-error @endif">
    {!! Form::label('first_name', 'Nombre(s)', ['class' => 'control-label']) !!}
    {!! Form::text('first_name', old('first_name', isset($patient) ? $patient->first_name : ''), ['class' =>
    'form-control underlined', 'placeholder' => 'Nombre(s)', isset($show) ? 'readonly' : '']) !!}
</div>
<div class="form-group @if($errors->has('birth_date')) has-error @endif">
    {!! Form::label('birth_date', 'Fecha de Nacimiento', ['class' => 'control-label']) !!}
</div>
<div class="form-inline">
    <div class="form-group  @if($errors->has('birth_date')) has-error @endif">
        <div id="dynamic-days">
        {!! Form::selectRange('birth_day', 1, 28, isset($patient) ? intval(substr($patient->birth_date, 8, 2)) : 1,
            ['class' => 'form-control', 'id' => 'birth_day', isset($show) ? 'disabled' : '']) !!}
        </div>
        &nbsp;&nbsp;
        {!! Form::select('birth_month', config('constants.months'), isset($patient) ?
        intval(substr($patient->birth_date, 5, 2)) : 1, ['class' => 'form-control', 'id' => 'birth_month', isset($show)
        ? 'disabled' : '']) !!}
        &nbsp;&nbsp;
        {!! Form::selectRange('birth_year', 1918, date("Y"), isset($patient) ? substr($patient->birth_date, 0, 4) :
        1970, ['class' => 'form-control', 'id' => 'birth_year', isset($show) ? 'disabled' : '']) !!}
    </div>
</div>
<br>