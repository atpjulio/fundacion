@extends('layouts.backend.template')

@push('styles')
    <link rel="stylesheet" href="{{asset('css/dataTables.bootstrap.min.css')}}">
@endpush

@section('content')
    <div class="title-block">
        <div class="float-left">
            <h3 class="title"> Administrar Productos </h3>
            <p class="title-description"> Aquí puedes ver el listado de todos los productos y crear, actualizar o eliminar cualquier producto </p>
        </div>
        <div class="float-right animated fadeInRight">
            <a href="{{ route('products.create') }}" class="btn btn-pill-left btn-primary btn-lg">
                <i class="fa fa-plus"></i>
                Nuevo Producto
            </a>
        </div>
    </div>

    <section class="section">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-block">
                        <div class="card-title-block">
                            <h3 class="title"> Productos registrados en el sistema </h3>
                        </div>
                        <div class="col-xs-12">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-condensed table-hover" id="myTable">
                                    <thead>
                                    <th style="width: 60px;">Imagen</th>
                                    <th class="">Nombre</th>
                                    <th style="width: 60px;">Disponibles</th>
                                    <th style="width: 60px;">Vendidos</th>
                                    <th style="width: 15%;">Opciones</th>
                                    </thead>
                                    <tbody>
                                    <tr>
                                        <td>
                                            <img src="{{ asset('img/products/default.jpg') }}" alt="" class="rounded" width="50">
                                        </td>
                                        <td>Producto número 1</td>
                                        <td>14</td>
                                        <td>2</td>
                                        <td>
                                            <a href="#" class="btn btn-pill-left btn-info btn-sm">
                                                Editar
                                            </a>
                                            <a href="#" class="btn btn-pill-right btn-danger btn-sm">
                                                Borrar
                                            </a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <img src="{{ asset('img/products/test1.jpg') }}" alt="" class="rounded" width="50">
                                        </td>
                                        <td>Bolso</td>
                                        <td>7</td>
                                        <td>9</td>
                                        <td>
                                            <a href="#" class="btn btn-pill-left btn-info btn-sm">
                                                Editar
                                            </a>
                                            <a href="#" class="btn btn-pill-right btn-danger btn-sm">
                                                Borrar
                                            </a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <img src="{{ asset('img/products/test2.jpg') }}" alt="" class="rounded" width="50">
                                        </td>
                                        <td>Bolso</td>
                                        <td>7</td>
                                        <td>9</td>
                                        <td>
                                            <a href="#" class="btn btn-pill-left btn-info btn-sm">
                                                Editar
                                            </a>
                                            <a href="#" class="btn btn-pill-right btn-danger btn-sm">
                                                Borrar
                                            </a>
                                        </td>
                                    </tr>
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