@extends('layouts.backend.template')

@section('content')
    <div class="title-block">
        <div class="float-left">
            <h3 class="title"> Actualizar Compañía </h3>
            <p class="title-description"> Actualizando compañía del sistema </p>
        </div>
        <div class="float-right animated fadeInRight">
            <a href="{{ route('company.index') }}" class="btn btn-pill-left btn-secondary btn-lg">
                <i class="fas fa-list"></i>
                Regresar
            </a>
        </div>
    </div>
    {!! Form::open(['route' => ['company.update', $company->id], 'method' => 'PUT', 'files' => true]) !!}
    <section class="section">
        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-block">
                        @include('company.fields')
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-block">
                        @include('partials.addresses')
                        @include('partials.phones')
                    </div>
                </div>
            </div>
            <div class="col-md-12 text-center">
                {!! Form::submit('Actualizar', ['class' => 'btn btn-oval btn-warning']) !!}
            </div>
        </div>
    </section>
    {!! Form::hidden('id', $company->id) !!}
    {!! Form::close() !!}
@endsection

@push('scripts')
    <script src="{{ asset('js/company/create.js') }}"></script>
@endpush
