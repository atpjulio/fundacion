
@extends('layouts.backend.template')

@section('content')
    {{-- dd($eps) --}}
    <div class="title-block">
        <div class="float-left">
            <h3 class="title"> Añadir Servicio para: {!! $eps->alias ? $eps->alias : $eps->name !!} </h3>
            <p class="title-description"> Añadiendo un nuevo servicio al sistema </p>
        </div>
        <div class="float-right animated fadeInRight">
            <a href="{{ route('eps.services.index', $eps->id) }}" class="btn btn-pill-left btn-secondary btn-lg">
                <i class="fas fa-list"></i>
                Regresar
            </a>
        </div>
    </div>

    <section class="section">
        <div class="row">
            <div class="col-md-6">
                {!! Form::open(['route' => 'eps.services.store', 'method' => 'POST']) !!}
                    <div class="card">
                        <div class="card-block">
                            @include('eps.services.fields')
                        </div>
                    </div>
                    <div class="text-center">
                        {!! Form::hidden('eps_id', $eps->id) !!}
                        {!! Form::submit('Guardar', ['class' => 'btn btn-oval btn-primary']) !!}
                    </div>
                {!! Form::close() !!}
            </div>
        </div>
    </section>
@endsection
