@extends('layouts.backend.template')

@section('content')
    <div class="title-block">
        <div class="float-left">
            <h3 class="title"> Actualizar EPS </h3>
            <p class="title-description"> Actualizando una EPS del sistema </p>
        </div>
        <div class="float-right animated fadeInRight">
            <a href="{{ route('eps.index') }}" class="btn btn-pill-left btn-secondary btn-lg">
                <i class="fas fa-list"></i>
                Regresar
            </a>
        </div>
    </div>

    {!! Form::open(['route' => ['eps.update', $eps->id], 'method' => 'PATCH']) !!}
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
            {!! Form::hidden('id', $eps->id) !!}
            <div class="col-md-12">
                <div class="text-center">
                    {!! Form::submit('Actualizar', ['class' => 'btn btn-oval btn-warning']) !!}
                </div>
            </div>
        </div>
    </section>
    {!! Form::close() !!}
@endsection

@push('scripts')
    <script src="{{asset('js/eps/create.js').'?version='.config('constants.stylesVersion')}}"></script>
@endpush
