@extends('layouts.backend.template')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/dataTables.bootstrap.min.css') }}">
@endpush

@section('content')
    <div class="title-block">
        <div class="float-left">
            <h3 class="title"> Comprobante de Egreso </h3>
            <p class="title-description"> Aquí puedes ver el listado de todas los comprabantes de egreso y crear, actualizar o eliminar cualquiera de ellos </p>
        </div>
        <div class="float-right animated fadeInRight">
            <a href="{{ route('egress.create') }}" class="btn btn-pill-left btn-primary btn-lg">
                <i class="fa fa-plus"></i>
                Nuevo
            </a>
        </div>
    </div>

    <section class="section">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-block">
                        <div class="card-title-block">
                            <h3 class="title"> Comprobantes de egreso registrados en el sistema </h3>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-condensed table-hover" id="myTable">
                                <thead>
                                <th style="width: 100px;"># Comprobante</th>
                                <th>Recibido de</th>
                                <th style="width: 110px;">Monto</th>
                                <th>Fecha</th>
                                <th style="width: 280px;">Opciones</th>
                                </thead>
                                <tbody>
                                @foreach($egresses as $egress)
                                    <tr>
                                        <td>{!! $egress->number !!}</td>
                                        <td>{!! $egress->entity->name !!}</td> 
                                        <td>$ {!! number_format($egress->amount, 2, ",", ".") !!}</td>
                                        <td>{!! \Carbon\Carbon::parse($egress->created_at)->format("d/m/Y") !!}</td>
                                        <td>
                                            <a href="{{ route('egress.edit', $egress->id) }}" class="btn btn-pill-left btn-info btn-sm">
                                                Editar
                                            </a>
                                            <a href="{{ route('egress.pdf', $egress->id) }}" class="btn btn-secondary btn-sm" target="_blank">
                                                Ver comprobante
                                            </a>
                                            <a href="javascript:showModal('egress-delete/{{ $egress->id }}')" class="btn btn-pill-right btn-danger btn-sm">
                                                Borrar
                                            </a>
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