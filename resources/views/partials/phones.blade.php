<div class="form-group  @if($errors->has('phone')) has-error @endif">
    {!! Form::label('phone', 'Teléfono', ['class' => 'control-label']) !!}
    {!! Form::text('phone', old('phone', isset($phone) ? $phone->phone : ''), ['class' => 'form-control underlined', 'placeholder' => 'Teléfono principal', 'maxlength' => 15]) !!}
</div>
<div class="form-group  @if($errors->has('phone2')) has-error @endif">
    {!! Form::label('phone2', 'Otro Teléfono (opcional)', ['class' => 'control-label']) !!}
    {!! Form::text('phone2', old('phone2', isset($phone) ? $phone->phone2 : ''), ['class' => 'form-control underlined', 'placeholder' => 'Otro teléfono', 'maxlength' => 15]) !!}
</div>