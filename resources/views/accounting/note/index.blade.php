@extends('layouts.backend.template')

@push('styles')
    <link rel="stylesheet" href="{{asset('css/dataTables.bootstrap.min.css')}}">
@endpush

@section('content')
    <div class="title-block">
        <div class="float-left">
            <h3 class="title"> Notas de Contabilidad </h3>
            <p class="title-description"> Aquí puedes ver el listado de todas las notas de contabilidad y crear, actualizar o eliminar cualquiera de ellas </p>
        </div>
        <div class="float-right animated fadeInRight">
            <a href="{{ route('accounting-note.create') }}" class="btn btn-pill-left btn-primary btn-lg">
                <i class="fa fa-plus"></i>
                Nueva Nota
            </a>
        </div>
    </div>

    <section class="section">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-block">
                        <div class="card-title-block">
                            <h3 class="title"> Notas de contabilidad registradas en el sistema </h3>
                        </div>
                        <div class="col-12">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-condensed table-hover" id="myTable">
                                    <thead>
                                    <th>Factura</th>
                                    <th>Monto</th>
                                    <th>Fecha</th>
                                    <th>Opciones</th>
                                    </thead>
                                    <tbody>
                                    @foreach($notes as $note)
                                        <tr>
                                            <td>{!! $note->invoice->number !!}</td>
                                            <td>$ {!! number_format($note->amount, 2, ",", ".") !!}</td>
                                            <td>{!! \Carbon\Carbon::parse($note->created_at)->format("d/m/Y") !!}</td>
                                            <td>
                                                <a href="{{ route('accounting-note.edit', $note->id) }}" class="btn btn-pill-left btn-info btn-sm">
                                                    Editar
                                                </a>
                                                <a href="" data-toggle="modal" data-target="#confirm-modal-{{ $note->id }}" class="btn btn-pill-right btn-danger btn-sm">
                                                    Borrar
                                                </a>
                                            </td>
                                            @include('accounting.note.delete_modal')
                                        </tr>
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
    <script src="{{asset('js/jquery.dataTables.min.js')}}"></script>
    <script src="{{asset('js/dataTables.bootstrap.min.js')}}"></script>
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