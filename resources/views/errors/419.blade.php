@extends('layouts.frontend.template')

@section('content')
    <div class="auth-content">
        <strong>            
            La página ha dejado de funcionar debido a inactividad
        </strong>
        <br><br>
        Por favor, ve hacia atrás actualiza la página e inténtalo nuevamente
        <br><br>
        Disculpas por las molestias causadas,<br>
        El equipo IT de {!! config('constants.companyInfo.longName') !!}
    </div>
@endsection
