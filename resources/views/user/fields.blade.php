<div class="form-group @if ($errors->has('full_name')) has-error @endif">
  <label for="full_name" class="control-label">Nombre completo</label>
  <input type="text" name="full_name" class="form-control underlined" placeholder="Nombre completo" value="{{ old('full_name', isset($user) ? $user->full_name : '') }}">
</div>
<div class="form-group @if ($errors->has('email')) has-error @endif">
  <label for="email" class="control-label">Correo electrónico</label>
  {!! Form::email('email', old('email', isset($user) ? $user->email : ''), ['class' => 'form-control underlined',
  'placeholder' => 'Correo electrónico']) !!}
</div>
<div class="form-group @if ($errors->has('password')) has-error @endif">
  <label for="password" class="control-label">Contraseña</label>
  {!! Form::password('password', ['class' => 'form-control underlined', 'placeholder' => '******']) !!}
</div>
<div class="form-group @if ($errors->has('password_confirmation')) has-error @endif">
  <label for="password_confirmation" class="control-label">Contraseña</label>
  {!! Form::password('password_confirmation', ['class' => 'form-control underlined', 'placeholder' => '******']) !!}
</div>
