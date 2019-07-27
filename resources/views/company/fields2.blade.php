<div class="form-group  @if($errors->has('state')) has-error @endif">
    {!! Form::label('state', 'Departamento', ['class' => 'control-label']) !!}
    <select name="state" id="state" class="form-control">
        @foreach(\App\State::getStates() as $code => $name)
            <option value="{{ sprintf("%02d", $code) }}"
                @if(isset($company) && $company->address->state == $code) selected
                @elseif($code == '08') selected @endif
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
            @foreach(\App\City::getCitiesByStateId((isset($company) and $company->address->state) ? $company->address->state : '08') as $code => $name)
            <option value="{{ sprintf("%03d", $code) }}" @if(isset($company) and $company->address->city == $code) selected @endif>
                {!! $code.' - '.$name !!}
            </option>
            @endforeach
        </select>
    </div>
</div>
