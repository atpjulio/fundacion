@extends('layouts.backend.template')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/dataTables.bootstrap.min.css') }}">
@endpush

@section('content')
    <div class="title-block">
        <div class="float-left">
            <h3 class="title"> Listado de Usuarios </h3>
            <p class="title-description"> Aquí puedes ver el listado de todas los usuarios y crear, actualizar o eliminar cualquiera de ellos </p>
        </div>
        <div class="float-right animated fadeInRight">
            <a href="{{ route('patient.create') }}" class="btn btn-pill-left btn-primary btn-lg">
                <i class="fa fa-plus"></i>
                Nuevo Usuario
            </a>
        </div>
    </div>

    <section class="section">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-block">
                        <div class="card-title-block">
                            <h3 class="title"> Usuarios registrados en el sistema </h3>
                        </div>
                        <div class="col-xs-12">
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
                                                <a href="{{ route('patient.edit', $patient->id) }}" class="btn btn-pill-left btn-info btn-sm">
                                                    Editar
                                                </a>
                                                <a href="" data-toggle="modal" data-target="#confirm-modal-{{ $patient->id }}" class="btn btn-pill-right btn-danger btn-sm">
                                                    Borrar
                                                </a>
                                            </td>
                                        </tr>
                                        @include('patient.delete_modal')
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
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
                }
            });
        } );
    </script>
@endpush