@extends('layouts.frontend.template')

@section('content')
    <div class="auth-content">
        <strong>            
            Uy, lo sentimos ha ocurrido un error en el sistema
        </strong>
        <br><br>
        Se ha enviado automáticamente un correo a soporte
        <br><br>
        Disculpas por las molestias causadas,<br>
        Haz clic <a href="{{ route('login') }}">aquí</a> para ir al inicio
        <br><br>
        El equipo IT de {!! config('constants.companyInfo.longName') !!}
    </div>
@endsection
