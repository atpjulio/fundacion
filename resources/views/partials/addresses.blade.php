<div class="form-group  @if($errors->has('address')) has-error @endif">
  <label for="address" class="control-label">Dirección</label>
    {!! Form::text('address', old('address', isset($address) ? $address->address : ''), ['class' => 'form-control underlined', 'placeholder' => 'Dirección', 'maxlength' => 50]) !!}
</div>
<div class="form-group  @if($errors->has('address2')) has-error @endif">
  <label for="address2" class="control-label">Dirección (continuación - opcional)</label>
    {!! Form::text('address2', old('address2', isset($address) ? $address->address2 : ''), ['class' => 'form-control underlined', 'placeholder' => 'Continuación de la dirección', 'maxlength' => 50]) !!}
</div>
<div class="form-group  @if($errors->has('state')) has-error @endif">
  <label for="state" class="control-label">Departamento</label>
    <select name="state" id="state" class="form-control">
        @foreach(\App\State::getStates() as $code => $name)
            <option value="{{ sprintf("%02d", $code) }}"
                @if(isset($address) && $address->state == $code) selected
                @elseif($code == '08') selected @endif
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
            @foreach(\App\City::getCitiesByStateId((isset($address) and $address->state) ? $address->state : '08') as $code => $name)
            <option value="{{ sprintf("%03d", $code) }}" @if(isset($address) and $address->city == $code) selected @endif>
                {!! $code.' - '.$name !!}
            </option>
            @endforeach
        </select>
    </div>
</div>
