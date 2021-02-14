<div class="form-group  @if($errors->has('phone')) has-error @endif">
  <label for="phone" class="control-label">Número de contacto</label>
  <input type="text" name="phone" maxlength="15" class="form-control underlined" value="{{ old('phone', (isset($patient) and $patient->phone) ? $patient->phone->phone : '') }}" placeholder="Número de contacto (opcional)">
</div>
<div class="form-group @if($errors->has('gender')) has-error @endif">
  <label for="gender" class="control-label">Sexo</label>
    {!! Form::select('gender', config('constants.gender'), old('gender', isset($patient) ? $patient->gender : ''), ['class' => 'form-control underlined', isset($show) ? 'readonly' : '']) !!}
</div>
<div class="form-group @if($errors->has('type')) has-error @endif">
  <label for="type" class="control-label">Tipo de usuario</label>
    {!! Form::select('type', config('constants.patientTypeString'), old('type', isset($patient) ? $patient->type : 2), ['class' => 'form-control underlined', isset($show) ? 'readonly' : '']) !!}
</div>
<div class="form-group  @if($errors->has('state')) has-error @endif">
  <label for="state" class="control-label">Departamento</label>
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
  <label for="city" class="control-label">Municipio</label>
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
  <label for="zone" class="control-label">Zona de residencia habitual</label>
    {!! Form::select('zone', config('constants.residenceZone'), old('zone', isset($patient) ? $patient->zone : ''), ['class' => 'form-control']) !!}
</div>
