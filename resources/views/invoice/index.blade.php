@extends('layouts.backend.template')

@push('styles')
    <link rel="stylesheet" href="{{asset('css/dataTables.bootstrap.min.css')}}">
@endpush

@section('content')
    <div class="title-block">
        <div class="float-left">
            <h3 class="title"> Administrar Facturas </h3>
            <p class="title-description"> Aquí puedes ver el listado de todos las facturas y crear, actualizar o eliminar cualquiera de ellas </p>
        </div>
        <div class="float-right animated fadeInRight">
            <a href="{{ route('invoice.create') }}" class="btn btn-pill-left btn-primary btn-lg">
                <i class="fa fa-plus"></i>
                Nueva Factura
            </a>
        </div>
    </div>

    <section class="section">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-block">
                        <div class="card-title-block">
                            <h3 class="title"> Facturas registradas en el sistema </h3>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-condensed table-hover" id="myTable">
                                <thead>
                                <th style="width: 60px;"># Factura</th>
                                <th>Autorización</th>
                                <th>Monto</th>
                                <th>Días</th>
                                <th style="width: 180px;">Opciones</th>
                                </thead>
                                <tbody>
                                    @foreach($invoices as $invoice)
                                    <tr>
                                        <td>{!! sprintf("%05d", $invoice->number) !!}</td>
                                        <td>{!! !$invoice->multiple ? $invoice->authorization_code : join(" | ", json_decode($invoice->multiple_codes, true)) !!}</td>
                                        <td>$ {!! !$invoice->multiple ? number_format($invoice->total, 2, ",", ".") : join(" | ", $invoice->multiple_totals_formated)!!}</td>
                                        <td>{!! !$invoice->multiple ? $invoice->days : join(" | ", json_decode($invoice->multiple_days, true)) !!}</td>
                                        <td>
                                            <a href="{{ route('invoice.edit', $invoice->id) }}" class="btn btn-pill-left btn-info btn-sm">
                                                Editar
                                            </a>
                                            <a href="{{ route('invoice.pdf', $invoice->id) }}" class="btn btn-secondary btn-sm" target="_blank">
                                                Ver factura
                                            </a>
                                            <a href="" data-toggle="modal" data-target="#confirm-modal-{{ $invoice->id }}" class="btn btn-pill-right btn-danger btn-sm">
                                                Borrar
                                            </a>
                                        </td>
                                        @include('invoice.delete_modal')
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
    <script src="{{ asset('js/general/table.js').'?version='.config('constants.stylesVersion') }}"></script>
@endpush