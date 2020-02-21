<div class="form-group  @if($errors->has('phone')) has-error @endif">
    {!! Form::label('phone', 'Número de contacto', ['class' => 'control-label']) !!}
    {!! Form::text('phone', old('phone', (isset($patient) and $patient->phone) ? $patient->phone->phone : ''), ['class' => 'form-control underlined', 'placeholder' => 'Número de contacto (opcional)', 'maxlength' => 15]) !!}
</div>
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
    <select name="state" id="state" class="form-control">
        @foreach(\App\State::getStates() as $code => $name)
            <option value="{{ sprintf("%02d", $code) }}"
                @if(isset($patient) && $patient->state == $code) selected
                @elseif($code == '44') selected @endif
                >
                {!! $code.' - '.$name !!}
            </option>
        @endforeach
    </select>
</div>
<div class="form-group  @if($errors->has('city')) has-error @endif">
    {!! Form::label('city', 'Municipio', ['class' => 'control-label']) !!}
    <div id="dynamic-cities">
        <select name="city" id="city" class="form-control">
            @foreach(\App\City::getCitiesByStateId((isset($patient) and $patient->state) ? $patient->state : '44') as $code => $name)
            <option value="{{ sprintf("%03d", $code) }}" @if(isset($patient) and $patient->city == $code) selected @endif>
                {!! $code.' - '.$name !!}
            </option>
            @endforeach
        </select>
    </div>
</div>
<div class="form-group  @if($errors->has('zone')) has-error @endif">
    {!! Form::label('zone', 'Zona de residencia habitual', ['class' => 'control-label']) !!}
    {!! Form::select('zone', config('constants.residenceZone'), old('zone', isset($patient) ? $patient->zone : ''), ['class' => 'form-control']) !!}
</div>
