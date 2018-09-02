@extends('layouts.backend.template')

@push('styles')
    <link rel="stylesheet" href="{{asset('css/dataTables.bootstrap.min.css')}}">
@endpush

@section('content')
    <div class="title-block">
        <div class="float-left">
            <h3 class="title"> Recibos de pago </h3>
            <p class="title-description"> Aquí puedes ver el listado de todos los recibos de pago y crear, actualizar o eliminar cualquiera de ellos </p>
        </div>
        <div class="float-right animated fadeInRight">
            <a href="{{ route('receipt.create') }}" class="btn btn-pill-left btn-primary btn-lg">
                <i class="fa fa-plus"></i>
                Nuevo Recibo
            </a>            
        </div>
        {{--  
        <div class="float-right animated fadeInRight">
            <a href="{{ route('receipt.create') }}" class="btn btn-pill-left btn-secondary btn-lg">
                <i class="fas fa-file-import"></i>
                Importar Recibos
            </a>
        </div>
        --}}
    </div>

    <section class="section">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-block">
                        <div class="card-title-block">
                            <h3 class="title"> Recibos registrados en el sistema </h3>
                        </div>
                        <div class="col-12">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-condensed table-hover" id="myTable">
                                    <thead>
                                    <th>Factura</th>
                                    <th>Monto del recibo</th>
                                    <th>Restante en factura</th>
                                    <th>Fecha</th>
                                    <th>Opciones</th>
                                    </thead>
                                    <tbody>
                                    @foreach($receipts as $receipt)
                                        <tr>
                                            <td>{!! $receipt->invoice->number !!}</td>
                                            <td>$ {!! number_format($receipt->amount, 2, ",", ".") !!}</td>
                                            <td>$ {!! number_format($receipt->invoice->total - $receipt->amount, 2, ",", ".") !!}</td>
                                            <td>{!! \Carbon\Carbon::parse($receipt->created_at)->format("d/m/Y") !!}</td>
                                            <td>
                                                <a href="{{ route('receipt.edit', $receipt->id) }}" class="btn btn-pill-left btn-info btn-sm">
                                                    Editar
                                                </a>
                                                <a href="" data-toggle="modal" data-target="#confirm-modal-{{ $receipt->id }}" class="btn btn-pill-right btn-danger btn-sm">
                                                    Borrar
                                                </a>
                                            </td>
                                            @include('accounting.receipt.delete_modal')
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