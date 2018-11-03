<div class="form-group  @if($errors->has('companion_dni')) has-error @endif">
    {!! Form::label('companion_dni', 'Documento del acompañante', ['class' => 'control-label']) !!}
    {!! Form::text('companion_dni', old('companion_dni', isset($authorization) ? $authorization->companion_dni : ''), ['class' => 'form-control underlined', 'placeholder' => 'Número de documento']) !!}
</div>
<div class="form-group  @if($errors->has('companion_name')) has-error @endif">
    {!! Form::label('companion_name', 'Nombre completo del acompañante', ['class' => 'control-label']) !!}
    {!! Form::text('companion_name', old('companion_name', isset($authorization) ? $authorization->companion_name : ''), ['class' => 'form-control underlined', 'placeholder' => 'Nombre completo']) !!}
</div>
