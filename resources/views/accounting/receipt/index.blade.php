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
            <a href="{{ route('receipt.import') }}" class="btn btn-pill-left btn-secondary btn-lg">
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
                                    <th style="width: 100px;"># Recibo</th>
                                    <th>Recibido de</th>
                                    <th style="width: 110px;">Monto del recibo</th>
                                    <th>Fecha</th>
                                    <th style="width: 200px;">Opciones</th>
                                    </thead>
                                    <tbody>
                                    @foreach($receipts as $receipt)
                                        <tr>
                                            <td>{!! $receipt->number !!}</td>
                                            <td>{!! $receipt->entity->name !!}</td>
                                            <td>$ {!! number_format($receipt->amount, 2, ",", ".") !!}</td>
                                            <td>{!! \Carbon\Carbon::parse($receipt->created_at)->format("d/m/Y") !!}</td>
                                            <td>
                                                <a href="{{ route('receipt.edit', $receipt->id) }}" class="btn btn-pill-left btn-info btn-sm">
                                                    Editar
                                                </a>
                                                <a href="{{ route('receipt.pdf', $receipt->id) }}" class="btn btn-secondary btn-sm" target="_blank">
                                                    Ver recibo
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
    <script src="{{ asset('js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('js/dataTables.bootstrap.min.js') }}"></script>
    <script src="{{ asset('js/general/table.js') }}"></script>
@endpush
