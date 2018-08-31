@extends('layouts.backend.template')

@section('content')
    <div class="title-block">
        <div class="float-left">
            <h3 class="title"> RIPs </h3>
            <p class="title-description"> Generación de RIPs </p>
        </div>
        <div class="float-right animated fadeInRight">
            <a href="{{ URL::previous() }}" class="btn btn-pill-left btn-secondary btn-lg">
                <i class="fas fa-list"></i>
                Regresar
            </a>
        </div>
    </div>
    <section class="section">
        @include('partials.build')
    </section>
@endsection