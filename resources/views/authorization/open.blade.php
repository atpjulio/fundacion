@extends('layouts.backend.template')

@push('styles')
    <link rel="stylesheet" href="{{asset('css/dataTables.bootstrap.min.css')}}">
@endpush

@section('content')
    <div class="title-block">
        <div class="float-left">
            <h3 class="title"> Listado de Autorizaciones que no tienen Factura (Total: {{ count($authorizations) }})</h3>
            <p class="title-description"> Aquí puedes ver el listado de las autorizaciones abiertas </p>
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
                            <h3 class="title"> Autorizaciones abiertas en el sistema </h3>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-condensed table-hover" id="myTable">
                                <thead>
                                <th>Código</th>
                                <th>EPS</th>
                                <th>Usuario</th>
                                <th>Días</th>
                                <th>Opciones</th>
                                </thead>
                                <tbody>
                                @foreach($authorizations as $authorization)
                                    <tr>
                                        <td>{!! $authorization->codec ?: '--' !!}</td>
                                        <td>{!! $authorization->eps->alias ? $authorization->eps->alias : $authorization->eps->name !!}</td>
                                        <td>{!! $authorization->patient ? $authorization->patient->back_name : '--' !!}</td>
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
    <script src="{{ asset('js/authorization/open.js') }}"></script>
@endpush