@extends('layouts.backend.template')

@push('styles')
    <link rel="stylesheet" href="{{asset('css/dataTables.bootstrap.min.css')}}">
@endpush

@section('content')
    <div class="title-block">
        <div class="float-left">
            <h3 class="title"> Listado de Autorizaciones Sin Número de Autorización  (Total: {{ count($authorizations) }})</h3>
            <p class="title-description"> Aquí puedes ver el listado de todas las autorizaciones incompletas </p>
        </div>
        <div class="float-right animated fadeInRight">
            <a href="{{ route('authorization.create') }}" class="btn btn-pill-left btn-primary btn-lg">
                <i class="fa fa-plus"></i>
                Nueva Autorización
            </a>
        </div>
    </div>

    <section class="section">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-block">
                        <div class="card-title-block">
                            <h3 class="title"> Autorizaciones incompletas registradas en el sistema </h3>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-condensed table-hover" id="myTable">
                                <thead>
                                <th>Código</th>
                                <th>EPS</th>
                                <th>Desde</th>
                                <th>Factura</th>
                                <th>Días</th>
                                <th>Opciones</th>
                                </thead>
                                <tbody>
                                @foreach($authorizations as $authorization)
                                    <tr>
                                        <td>{!! $authorization->codec ?: '--' !!}</td>
                                        <td>{!! $authorization->eps->code !!} - {!! $authorization->eps->alias ? $authorization->eps->alias : $authorization->eps->name !!}</td>
                                        <td>{!! \Carbon\Carbon::parse($authorization->date_from)->format("d/m/Y") !!}</td>
                                        <td>{!! $authorization->invoice ? $authorization->invoice->number : '--' !!}</td>
                                        <td>{!! $authorization->days !!}</td>
                                        <td>
                                            @role('admin')
                                            <a href="{{ route('authorization.edit', $authorization->id) }}" class="btn btn-pill-left btn-info btn-sm">
                                                Editar
                                            </a>
                                            <a href="{{ route('authorization.excel', $authorization->id) }}" class="btn btn-secondary btn-sm">
                                                Planilla
                                            </a>
                                            <a href="javascript:showModal('authorization/delete/{{ $authorization->id }}')" class="btn btn-pill-right btn-danger btn-sm">
                                                Borrar
                                            </a>
                                            @endrole
                                            @role('user')
                                            <a href="{{ route('authorization.edit', $authorization->id) }}" class="btn btn-pill-left btn-info btn-sm">
                                                Editar
                                            </a>
                                            <a href="{{ route('authorization.excel', $authorization->id) }}" class="btn btn-pill-right btn-secondary btn-sm">
                                                Planilla
                                            </a>
                                            @endrole
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
    </section>
@endsection

@push('scripts')
    <script src="{{ asset('js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('js/dataTables.bootstrap.min.js') }}"></script>
    <script src="{{ asset('js/general/table.js') }}"></script>
@endpush