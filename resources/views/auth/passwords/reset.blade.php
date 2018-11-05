@extends('layouts.frontend.template')

@section('content')
    <div class="auth-content">
        <form method="POST" action="{{ route('password.request') }}" aria-label="{{ __('Reset Password') }}">
            @csrf
            @include('partials.messages')
            <input type="hidden" name="token" value="{{ $token }}">

            <div class="form-group">
                <label for="email">Confirma tu email</label>
                <input type="email" class="form-control underlined" name="email" id="email" placeholder="Confirma tu email" required> 
            </div>
            <div class="form-group">
                <label for="password">Nueva contraseña</label>
                <input type="password" class="form-control underlined" name="password" id="password" required> 
            </div>
            <div class="form-group">
                <label for="password_confirmation">Confirma tu nueva contraseña</label>
                <input type="password" class="form-control underlined" name="password_confirmation" id="password_confirmation" required> 
            </div>
            <div class="form-group">
                <button type="submit" class="btn btn-block btn-warning">Restaurar contraseña</button>
            </div>
        </form>
    </div>
@endsection
