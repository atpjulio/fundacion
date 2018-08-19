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

    {!! Form::open(['route' => ['authorization.update', $authorization->id], 'method' => 'PUT', 'id' => 'myForm']) !!}
    {!! Form::hidden('id', $authorization->id) !!}
    <section class="section">
        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-block">
                        <div class="card-title-block">
                            <h3 class="title"> Información de la Autorización </h3>
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
                    </div>
                </div>
            </div>
            <div class="col-12">
                <div class="card">
                    <div class="card-block">
                        <div class="card-title-block">
                            <h3 class="title"> Usuario al que pertenece esta autorización
                                {!! Form::button('Nuevo Usuario', ['class' => 'btn btn-oval btn-info float-right']) !!}
                            </h3>
                        </div>
                        <div class="col-12">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-condensed table-hover" id="myTable">
                                    <thead>
                                    <th>Tipo Doc.</th>
                                    <th>Documento</th>
                                    <th>Nombre Completo</th>
                                    <th>Fecha Nac.</th>
                                    <th>Edad</th>
                                    <th>Opciones</th>
                                    </thead>
                                    <tbody>
                                    @foreach($patients as $patient)
                                        <tr>
                                            <td>{!! $patient->dni_type !!}</td>
                                            <td>{!! $patient->dni !!}</td>
                                            <td>{!! $patient->full_name !!}</td>
                                            <td>{!! $patient->birth_date !!}</td>
                                            <td>{!! $patient->age !!}</td>
                                            <td>
                                                <button type="button" class="btn btn-oval btn-warning btn-sm" onclick="sendInfo({{ $patient->id }})">
                                                    Actualizar
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            {{--
            <div class="col-md-12">
                <div class="text-center">
                    {!! Form::submit('Guardar', ['class' => 'btn btn-oval btn-primary']) !!}
                </div>
            </div>
            --}}
        </div>
    </section>
    {!! Form::hidden('patient_id', null, ['id' => 'patient_id']) !!}
    {!! Form::close() !!}
@endsection

@push('scripts')
    <script src="{{ asset('js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('js/dataTables.bootstrap.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            $('#myTable').DataTable({
                "language": {
                    "lengthMenu": "Mostrando _MENU_ registros por página",
                    "zeroRecords": "No se encontró ningún resultado",
                    "info": "Mostrando página _PAGE_ de _PAGES_",
                    "infoEmpty": "No hay información disponible",
                    "infoFiltered": "(filtrando de un total de _MAX_ registros)",
                    "search":         "Buscar:",
                    "paginate": {
                        "first":      "Primera",
                        "last":       "Última",
                        "next":       "Siguiente",
                        "previous":   "Anterior"
                    }
                },
                "oSearch": {"sSearch": "{{ $authorization->patient->dni }}"}
            });
        } );
        function sendInfo(id) {
            $('#patient_id').val(id);
            $('#myForm').submit();
        }
    </script>
@endpush