@extends('layouts.backend.template')

@section('content')
    <div class="title-block">
        <div class="float-left">
            <h3 class="title"> Nuevo Producto </h3>
            <p class="title-description"> Añadiendo nuevo producto al sistema </p>
        </div>
        <div class="float-right animated fadeInRight">
            <a href="{{ route('products.index') }}" class="btn btn-pill-left btn-secondary btn-lg">
                <i class="fas fa-cog"></i>
                Regresar
            </a>
        </div>
    </div>

    <section class="section">
        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-block">
                        @include('products.fields')
                        <div class="text-center">
                            {!! Form::submit('Guardar', ['class' => 'btn btn-oval btn-primary']) !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
