<div class="form-group  @if($errors->has('dni_type')) has-error @endif">
    {!! Form::label('dni_type', 'Departamento', ['class' => 'control-label']) !!}
    {!! Form::select('dni_type', config('constants.documentTypes'), old('dni_type', isset($patient) ? $patient->dni_type : ''), ['class' => 'form-control']) !!}
</div>
<div class="form-group @if($errors->has('dni')) has-error @endif">
    {!! Form::label('dni', 'Número de documento', ['class' => 'control-label']) !!}
    {!! Form::text('dni', old('dni', isset($patient) ? $patient->dni : ''), ['class' => 'form-control underlined', 'placeholder' => 'Número de documento']) !!}
</div>
<div class="form-group @if($errors->has('first_name')) has-error @endif">
    {!! Form::label('first_name', 'Nombre(s)', ['class' => 'control-label']) !!}
    {!! Form::text('first_name', old('first_name', isset($patient) ? $patient->first_name : ''), ['class' => 'form-control underlined', 'placeholder' => 'Nombre(s)']) !!}
</div>
<div class="form-group @if($errors->has('last_name')) has-error @endif">
    {!! Form::label('last_name', 'Apellido(s)', ['class' => 'control-label']) !!}
    {!! Form::text('last_name', old('last_name', isset($patient) ? $patient->last_name : ''), ['class' => 'form-control underlined', 'placeholder' => 'Apellido(s)']) !!}
</div>
<div class="form-group @if($errors->has('birth_date')) has-error @endif">
    {!! Form::label('birth_date', 'Fecha de Nacimiento', ['class' => 'control-label']) !!}
</div>
<div class="form-inline">
    <div class="form-group  @if($errors->has('birth_date')) has-error @endif">
        {!! Form::selectRange('birth_year', 1918, date("Y"), isset($patient) ? substr($patient->birth_date, 0, 4) : 1970, ['class' => 'form-control', 'id' => 'birth_year']) !!}
        &nbsp;&nbsp;
        {!! Form::select('birth_month', config('constants.months'), isset($patient) ? intval(substr($patient->birth_date, 5, 2)) : 1, ['class' => 'form-control', 'id' => 'birth_month']) !!}
        &nbsp;&nbsp;
        <div id="dynamic-days">
            {!! Form::selectRange('birth_day', 1, 28, isset($patient) ? intval(substr($patient->birth_date, 8, 2)) : 1, ['class' => 'form-control', 'id' => 'birth_day']) !!}
        </div>
    </div>
</div>
<br>
<div class="form-group @if($errors->has('sisben_level')) has-error @endif">
    {!! Form::label('sisben_level', 'Nivel Sisben (opcional)', ['class' => 'control-label']) !!}
    {!! Form::number('sisben_level', old('sisben_level', isset($patient) ? $patient->sisben_level : ''), ['class' => 'form-control underlined', 'placeholder' => 'Nivel Sisben', 'min' => 0]) !!}
</div>
