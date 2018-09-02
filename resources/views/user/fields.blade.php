<div class="form-group @if($errors->has('full_name')) has-error @endif">
    {!! Form::label('full_name', 'Nombre completo', ['class' => 'control-label']) !!}
    {!! Form::text('full_name', old('full_name', isset($user) ? $user->full_name : ''), ['class' => 'form-control underlined', 'placeholder' => 'Nombre completo']) !!}
</div>
<div class="form-group @if($errors->has('email')) has-error @endif">
    {!! Form::label('email', 'Correo electrónico', ['class' => 'control-label']) !!}
    {!! Form::email('email', old('email', isset($user) ? $user->email : ''), ['class' => 'form-control underlined', 'placeholder' => 'Correo electrónico']) !!}
</div>
<div class="form-group @if($errors->has('password')) has-error @endif">
    {!! Form::label('password', 'Contraseña', ['class' => 'control-label']) !!}
    {!! Form::password('password', ['class' => 'form-control underlined', 'placeholder' => '******']) !!}
</div>
<div class="form-group @if($errors->has('password_confirmation')) has-error @endif">
    {!! Form::label('password_confirmation', 'Contraseña', ['class' => 'control-label']) !!}
    {!! Form::password('password_confirmation', ['class' => 'form-control underlined', 'placeholder' => '******']) !!}
</div>
