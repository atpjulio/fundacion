@extends('layouts.frontend.template')

@section('content')
    <div class="auth-content">
        <form method="POST" action="{{ route('login') }}" aria-label="{{ __('Login') }}" id="login-form" >
            @csrf
            @include('partials.messages')
            <div class="form-group">
                <label for="username">Email</label>
                <input type="email" class="form-control underlined" name="email" id="username" placeholder="Tu correo electrónico" required> 
            </div>
            <div class="form-group">
                <label for="password">Contraseña</label>
                <input type="password" class="form-control underlined" name="password" id="password" placeholder="Tu contraseña" required>
            </div>
            <div class="form-group">
                {{--  
                <label for="remember">
                    <input class="checkbox" id="remember" type="checkbox">
                    <span>Recuérdame</span>
                </label>
                --}}
                <a href="{{ route('password.request') }}" class="forgot-btn mb-3 pull-right">¿Olvidaste la contraseña?</a>
            </div>
            <div class="form-group">
                <button type="submit" class="btn btn-block btn-primary">Ingresar</button>
            </div>
            <!--
            <div class="form-group">
                <p class="text-muted text-center">Do not have an account?
                    <a href="signup.html">Sign Up!</a>
                </p>
            </div>
            -->
        </form>
    </div>
@endsection
