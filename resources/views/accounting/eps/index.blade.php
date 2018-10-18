@extends('layouts.backend.template')

@push('styles')
    <link rel="stylesheet" href="{{asset('css/dataTables.bootstrap.min.css')}}">
@endpush

@section('content')
    <div class="title-block">
        <div class="float-left">
            <h3 class="title"> Facturas para EPS: {!! $invoices[0]->eps->alias ?: $invoices[0]->eps->name !!}</h3>
            <p class="title-description"> Detalle de facturas para una EPS en específico </p>
        </div>
        <div class="float-right animated fadeInRight">
            <a href="{{ route('accounting.eps') }}" class="btn btn-pill-left btn-primary btn-lg">
                <i class="fas fa-list"></i>
                Regresar
            </a>
        </div>
    </div>

    <section class="section">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-block">
                        <div class="card-title-block">
                            <h3 class="title"> Facturas registradas en el sistema para {!! $invoices[0]->eps->alias ?: $invoice[0]->eps->name !!}</h3>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-condensed table-hover" id="myTable">
                                <thead>
                                <th># Factura</th>
                                <th>Autorización</th>
                                <th>Monto</th>
                                <th>Pagado</th>
                                <th>Pendiente</th>
                                </thead>
                                <tbody>
                                    @foreach($invoices as $invoice)
                                    <tr>
                                        <td>{!! sprintf("%05d", $invoice->number) !!}</td>
                                        <td>{!! $invoice->authorization_code !!}</td>
                                        <td>$ {!! number_format($invoice->total, 2, ",", ".") !!}</td>
                                        <td>$ {!! number_format($invoice->payment, 2, ",", ".") !!}</td>
                                        <td>$ {!! number_format($invoice->total - $invoice->payment, 2, ",", ".") !!}</td>
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