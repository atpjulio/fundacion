@extends('layouts.backend.template')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/dataTables.bootstrap.min.css') }}">
@endpush

@section('content')
    <div class="title-block">
        <div class="float-left">
            <h3 class="title"> Actualizar Autorización </h3>
            <p class="title-description"> Actualizando una autorización del sistema </p>
        </div>
        <div class="float-right animated fadeInRight">
            <a href="{{ route('authorization.index') }}" class="btn btn-pill-left btn-secondary btn-lg">
                <i class="fas fa-list"></i>
                Regresar
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
                            </h3>
                        </div>
                        <div class="col-12" id="dynamic-patients">
                            @include('partials._eps_patients_edit')
                        </div>
                    </div>
                </div>
            </div>
        </div>
        {!! Form::open(['route' => ['authorization.update', $authorization->id], 'method' => 'PUT', 'id' => 'myForm']) !!}
        {!! Form::hidden('id', $authorization->id) !!}
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-block">
                        <div class="card-title-block">
                            <h3 class="title" id="authFields"> Paciente seleccionado </h3>
                        </div>
                        <p id="selected_patient">{!! $authorization->patient->full_name !!}</p>
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
                    {!! Form::submit('Actualizar', ['class' => 'btn btn-oval btn-warning']) !!}
                </div>
            </div>
        </div>
        {!! Form::hidden('patient_id', $authorization->patient->id, ['id' => 'patient_id']) !!}
        {!! Form::close() !!}
    </section>
@endsection

@push('scripts')
    <script src="{{ asset('js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('js/dataTables.bootstrap.min.js') }}"></script>
    <script src="{{ asset('js/authorization/edit.js') }}"></script>
@endpush