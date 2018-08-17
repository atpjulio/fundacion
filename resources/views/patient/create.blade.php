@extends('layouts.backend.template')

@section('content')
    <div class="title-block">
        <div class="float-left">
            <h3 class="title"> Nuevo Usuario </h3>
            <p class="title-description"> Añadiendo un nuevo usuario al sistema </p>
        </div>
        <div class="float-right animated fadeInRight">
            <a href="{{ route('patient.index') }}" class="btn btn-pill-left btn-secondary btn-lg">
                <i class="fas fa-list"></i>
                Regresar
            </a>
        </div>
    </div>

    {!! Form::open(['route' => 'patient.store', 'method' => 'POST']) !!}
    <section class="section">
        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-block">
                        <div class="card-title-block">
                            <h3 class="title"> Información Básica </h3>
                        </div>
                        @include('patient.fields')
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
                <div class="text-center">
                    {!! Form::submit('Guardar', ['class' => 'btn btn-oval btn-primary']) !!}
                </div>
            </div>
        </div>
    </section>
    {!! Form::close() !!}
@endsection

@push('scripts')
    <script language="javascript" type="text/javascript">
        $(document).ready(function() {
            $('#birth_year').on('change', function (e) {
                fillDays($('#birth_year').val() + "-" + $('#birth_month').val());
            });
            $('#birth_month').on('change', function (e) {
                fillDays($('#birth_year').val() + "-" + $('#birth_month').val());
            });
        });
    </script>
@endpush
