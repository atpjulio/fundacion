<div class="form-group  @if($errors->has('address')) has-error @endif">
    {!! Form::label('address', 'Dirección', ['class' => 'control-label']) !!}
    {!! Form::text('address', old('address', isset($address) ? $address->address : ''), ['class' => 'form-control underlined', 'placeholder' => 'Dirección', 'maxlength' => 50]) !!}
</div>
<div class="form-group  @if($errors->has('address2')) has-error @endif">
    {!! Form::label('address2', 'Dirección (continuación - opcional)', ['class' => 'control-label']) !!}
    {!! Form::text('address2', old('address2', isset($address) ? $address->address2 : ''), ['class' => 'form-control underlined', 'placeholder' => 'Continuación de la dirección', 'maxlength' => 50]) !!}
</div>
<div class="form-group  @if($errors->has('city')) has-error @endif">
    {!! Form::label('city', 'Municipio', ['class' => 'control-label']) !!}
    {!! Form::text('city', old('city', isset($address) ? $address->city : ''), ['class' => 'form-control underlined', 'placeholder' => 'Municipio']) !!}
</div>
<div class="form-group  @if($errors->has('state')) has-error @endif">
    {!! Form::label('state', 'Departamento', ['class' => 'control-label']) !!}
    {!! Form::select('state', \App\State::getStates(), old('state', isset($address) ? $address->state : ''), ['class' => 'form-control']) !!}
</div>
