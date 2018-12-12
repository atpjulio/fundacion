@extends('layouts.backend.template')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/dataTables.bootstrap.min.css') }}">
@endpush

@section('content')
    <div class="title-block">
        <div class="float-left">
            <h3 class="title"> Añadir Autorización </h3>
            <p class="title-description"> Añadiendo una autorización nueva al sistema </p>
        </div>
        <div class="float-right animated fadeInRight">
            <a href="{{ route('authorization.index') }}" class="btn btn-pill-left btn-secondary btn-lg">
                <i class="fas fa-list"></i>
                Listado
            </a>
        </div>
    </div>

    <section class="section">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-block">
                        <div class="card-title-block">
                            <h3 class="title"> Usuario al que pertenece esta autorización
                                <a href="{{ route('patient.create.authorization') }}" class="btn btn-oval btn-info float-right">Nuevo Usuario</a>
                            </h3>
                        </div>
                        <div class="col-12" id="dynamic-patients">
                            @include('partials._eps_patients')
                        </div>
                    </div>
                </div>
            </div>
        </div>
        {!! Form::open(['route' => 'authorization.store', 'method' => 'POST', 'id' => 'myForm']) !!}
        <div id="restOfFields" style="display: none;">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-block">
                            <div class="card-title-block">
                                <h3 class="title" id="authFields"> Paciente seleccionado </h3>
                            </div>
                            <h2 id="selected_patient"></h2>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-block">
                            <div class="card-title-block">
                                <h3 class="title" id="authFields"> Información de la Autorización </h3>
                            </div>
                            @include('authorization.fields')
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-block">
                            <div class="card-title-block">
                                <h3 class="title"> Fecha de validez </h3>
                            </div>
                            @include('authorization.fields2')
                            <div id="companionsDiv" @if (old('companion')) style="display: block;" @else style="display: none;" @endif>
                                @include('authorization.companion')
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="text-center">
                        {!! Form::submit('Guardar', ['class' => 'btn btn-oval btn-primary']) !!}
                    </div>
                </div>
            </div>
        </div>
        {!! Form::hidden('patient_id', null, ['id' => 'patient_id']) !!}
        {!! Form::close() !!}
    </section>
@endsection

@push('scripts')
    <script src="{{ asset('js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('js/dataTables.bootstrap.min.js') }}"></script>
    <script src="{{ asset('js/authorization/index.js') }}"></script>
@endpush
