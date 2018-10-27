@extends('layouts.backend.template')

@push('styles')
    <link rel="stylesheet" href="{{asset('css/dataTables.bootstrap.min.css')}}">
@endpush

@section('content')
    <div class="title-block">
        <div class="float-left">
            <h3 class="title"> Listado de EPS </h3>
            <p class="title-description"> Aquí puedes ver el listado de todas las EPS y crear, actualizar o eliminar cualquiera de ellas </p>
        </div>
        <div class="float-right animated fadeInRight">
            <a href="{{ route('eps.create') }}" class="btn btn-pill-left btn-primary btn-lg">
                <i class="fa fa-plus"></i>
                Añadir EPS
            </a>
        </div>
    </div>

    <section class="section">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-block">
                        <div class="card-title-block">
                            <h3 class="title"> EPS registradas en el sistema </h3>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-condensed table-hover" id="myTable">
                                <thead>
                                <th>Código</th>
                                <th>Nombre</th>
                                <th>Tarifa diaria</th>
                                <th>Usuarios</th>
                                <th>Opciones</th>
                                </thead>
                                <tbody>
                                @foreach($epss as $eps)
                                    <tr>
                                        <td>{!! $eps->code !!}</td>
                                        <td>{!! $eps->alias ? $eps->alias : $eps->name !!}</td>
                                        <td>$ {!! number_format($eps->daily_price, 2, ',', '.') !!}</td>
                                        <td>{!! $eps->total_patients !!}</td>
                                        <td>
                                            <a href="{{ route('eps.edit', $eps->id) }}" class="btn btn-pill-left btn-info btn-sm">
                                                Editar
                                            </a>
                                            <a href="{{ route('eps.services.index', $eps->id) }}" class="btn btn-secondary btn-sm">
                                                Servicios
                                            </a>
                                            <a href="" data-toggle="modal" data-target="#confirm-modal-{{ $eps->id }}" class="btn btn-pill-right btn-danger btn-sm">
                                                Borrar
                                            </a>
                                        </td>
                                    </tr>
                                    @include('eps.delete_modal')
                                @endforeach
                                </tbody>
                            </table>
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
    <script src="{{ asset('js/general/table.js') }}"></script>
@endpush