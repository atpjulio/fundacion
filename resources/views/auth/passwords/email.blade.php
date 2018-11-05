@extends('layouts.frontend.template')

@section('content')
    <div class="auth-content">
        <form method="POST" action="{{ route('password.email') }}" aria-label="{{ __('Reset Password') }}">
            @csrf
            @include('partials.messages')
            <div class="form-group">
                <label for="email">Email para recuperar tu contraseña</label>
                <input type="email" class="form-control underlined" name="email" id="email" placeholder="Tu correo electrónico" required> 
            </div>
            <div class="form-group">
                <button type="submit" class="btn btn-block btn-primary">Enviar enlace de recuperación</button>
            </div>
        </form>
    </div>
@endsection
