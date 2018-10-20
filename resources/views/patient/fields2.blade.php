<div class="form-group @if($errors->has('gender')) has-error @endif">
    {!! Form::label('gender', 'Sexo', ['class' => 'control-label']) !!}
    {!! Form::select('gender', config('constants.gender'), old('gender', isset($patient) ? $patient->gender : ''), ['class' => 'form-control underlined', isset($show) ? 'readonly' : '']) !!}
</div>
<div class="form-group @if($errors->has('type')) has-error @endif">
    {!! Form::label('type', 'Tipo de usuario', ['class' => 'control-label']) !!}
    {!! Form::select('type', config('constants.patientTypeString'), old('type', isset($patient) ? $patient->type : 2), ['class' => 'form-control underlined', isset($show) ? 'readonly' : '']) !!}
</div>
<div class="form-group  @if($errors->has('state')) has-error @endif">
    {!! Form::label('state', 'Departamento', ['class' => 'control-label']) !!}
    {!! Form::select('state', \App\State::getStates(), old('state', isset($patient) ? $patient->state : '08'), ['class' => 'form-control', 'id' => 'state']) !!}
</div>
<div class="form-group  @if($errors->has('city')) has-error @endif">
    {!! Form::label('city', 'Municipio', ['class' => 'control-label']) !!}
    <div id="dynamic-cities">
        {!! Form::select('city', \App\City::getCitiesByStateId(isset($patient) ? $patient->state : '08'), old('city', isset($patient) ? $patient->city : ''), ['class' => 'form-control']) !!}
    </div>
</div>
<div class="form-group  @if($errors->has('zone')) has-error @endif">
    {!! Form::label('zone', 'Zona de residencia habitual', ['class' => 'control-label']) !!}
    {!! Form::select('zone', config('constants.residenceZone'), old('zone', isset($patient) ? $patient->zone : ''), ['class' => 'form-control']) !!}
</div>
