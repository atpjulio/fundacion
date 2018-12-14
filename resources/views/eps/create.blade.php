@extends('layouts.backend.template')

@section('content')
    <div class="title-block">
        <div class="float-left">
            <h3 class="title"> Añadir EPS </h3>
            <p class="title-description"> Añadiendo una EPS nueva al sistema </p>
        </div>
        <div class="float-right animated fadeInRight">
            <a href="{{ route('eps.index') }}" class="btn btn-pill-left btn-secondary btn-lg">
                <i class="fas fa-list"></i>
                Listado
            </a>
        </div>
    </div>

    {!! Form::open(['route' => 'eps.store', 'method' => 'POST']) !!}
    <section class="section">
        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-block">
                        <div class="card-title-block">
                            <h3 class="title"> Información Básica </h3>
                        </div>
                        @include('eps.fields')
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-block">
                        <div class="card-title-block">
                            <h3 class="title"> Dirección </h3>
                        </div>
                        @include('partials.addresses')
                        @include('partials.phones')
                    </div>
                </div>
            </div>
            <div class="col-md-12">
              @include('partials._eps_prices')
            </div>
            <div class="col-md-12">
                <div class="text-center">
                    {!! Form::submit('Guardar', ['class' => 'btn btn-oval btn-primary']) !!}
                </div>
            </div>
        </div>
    </section>
    {!! Form::close() !!}
@endsection

@push('scripts')
    <script src="{{asset('js/eps/create.js').'?version='.config('constants.stylesVersion')}}"></script>
@endpush
