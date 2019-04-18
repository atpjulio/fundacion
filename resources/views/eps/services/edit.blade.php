
@extends('layouts.backend.template')

@section('content')
    {{-- dd($eps) --}}
    <div class="title-block">
        <div class="float-left">
            <h3 class="title"> Editar Servicio para: {!! $eps->alias ? $eps->alias : $eps->name !!} </h3>
            <p class="title-description"> Mostrando / actualizando servicio de EPS </p>
        </div>
        <div class="float-right animated fadeInRight">
            <a href="{{ route('eps.services.index', $eps->id) }}" class="btn btn-pill-left btn-secondary btn-lg">
                <i class="fas fa-list"></i>
                Regresar
            </a>
        </div>
    </div>

    <section class="section">
        <div class="row justify-content-center">
            <div class="col-md-6">
                {!! Form::open(['route' => ['eps.services.update', $service->id], 'method' => 'PATCH']) !!}
                    <div class="card">
                        <div class="card-block">
                            @include('eps.services.fields')
                        </div>
                    </div>
                    <div class="text-center">
                        {!! Form::hidden('id', $service->id) !!}
                        {!! Form::hidden('eps_id', $eps->id) !!}
                        {!! Form::submit('Actualizar', ['class' => 'btn btn-oval btn-warning']) !!}
                    </div>
                {!! Form::close() !!}
            </div>
        </div>
    </section>
@endsection
