@extends('layouts.backend.template')

@section('content')
    <div class="title-block">
        <div class="float-left">
            <h3 class="title"> Nueva Factura </h3>
            <p class="title-description"> Añadiendo nueva factura al sistema </p>
        </div>
        <div class="float-right animated fadeInRight">
            <a href="{{ route('invoice.index') }}" class="btn btn-pill-left btn-secondary btn-lg">
                <i class="fas fa-list"></i>
                Regresar
            </a>
        </div>
    </div>
    <section class="section">
        @include('partials.build')
    </section>
{{--
    <section class="section">
        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-block">
                        @include('index.fields')
                        <div class="text-center">
                            {!! Form::submit('Guardar', ['class' => 'btn btn-oval btn-primary']) !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    --}}
@endsection
