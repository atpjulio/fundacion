@extends('layouts.backend.template')

@push('styles')
    <link rel="stylesheet" href="{{asset('css/dataTables.bootstrap.min.css')}}">
@endpush

@section('content')
    <div class="title-block">
        <div class="float-left">
            <h3 class="title"> Nueva Nota de Contabilidad </h3>
            <p class="title-description"> Añadiendo nueva nota de contabilidad al sistema </p>
        </div>
        <div class="float-right animated fadeInRight">
            <a href="{{ route('accounting-note.index') }}" class="btn btn-pill-left btn-secondary btn-lg">
                <i class="fas fa-list"></i>
                Regresar
            </a>
        </div>
    </div>
    {!! Form::open(['route' => 'accounting-note.store', 'method' => 'POST']) !!}
    <section class="section">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-block">
                        <div class="card-title-block">
                            <h3 class="title"> Selecciona la factura para esta nota de contabilidad </h3>
                        </div>
                        <div class="col-12">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-condensed table-hover" id="myTable">
                                    <thead>
                                    <th>Número</th>
                                    <th>Autorización</th>
                                    <th>Monto</th>
                                    <th>Fecha</th>
                                    <th>Opciones</th>
                                    </thead>
                                    <tbody>
                                    @foreach($invoices as $invoice)
                                        <tr>
                                            <td>{!! $invoice->number !!}</td>
                                            <td>{!! $invoice->authorization_code !!}</td>
                                            <td>$ {!! number_format($invoice->total, 2, ",", ".") !!}</td>
                                            <td>{!! \Carbon\Carbon::parse($invoice->created_at)->format("d/m/Y") !!}</td>
                                            <td>
                                                {!! Form::button('Seleccionar', ['class' => 'btn btn-oval btn-success' ]) !!}
                                                {!! Form::hidden('current_invoice_id', $invoice->id) !!}
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
            <div class="col-md-6" id="beginning">
                <div class="card">
                    <div class="card-block">
                        @include('accounting.note.fields')
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-block">
                        @include('accounting.note.fields2')
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <div class="card">
                    <div class="card-block">
                        @include('accounting.note.fields3')
                    </div>
                </div>
            </div>
            <div class="col-12">
                <div class="text-center">
                    {!! Form::submit('Guardar la nota', ['class' => 'btn btn-oval btn-primary']) !!}
                </div>
            </div>
        </div>
    </section>
    {!! Form::hidden('invoice_id', null, ['id' => 'invoice_id']) !!}
    {!! Form::close() !!}
@endsection

@push('scripts')
    <script src="{{asset('js/jquery.dataTables.min.js')}}"></script>
    <script src="{{asset('js/dataTables.bootstrap.min.js')}}"></script>
    <script>
        $(document).ready(function() {
            $('#total').val($('#selected_price').val() * $('#total_days').val());
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
            $('.btn-success').click(function() {
                // console.log($(this).parent().parent().find('td')[5].outerText);
                $('#invoice_id').val($(this).parent().find('input')[0].value);
                $('#invoice_number').val($(this).parent().parent().find('td').first()[0].outerText);
                $('html, body').animate({
                    scrollTop: $('#beginning').offset().top
                }, 300, function(){
                    // Add hash (#) to URL when done scrolling (default click behavior)
                    window.location.href = '#beginning';
                });
            });
            $('#total_days').on('change', function (e) {
                $('#total').val($('#selected_price').val() * $('#total_days').val());
            });
            $(".addRow").click(function(){
                $("#pucsTable").append('<tr>' +
                    '<td><input type="text" id="notePucs" name="notePucs[]" value="" class="form-control" placeholder="Código PUC"/></td>' +
                    '<td><input type="text" name="puc_description" placeholder="Cuentas" class="form-control"></td>' +
                    '<td><input type="text" name="puc_debit" placeholder="Débitos" class="form-control"></td>' +
                    '<td><input type="text" name="puc_credit" placeholder="Créditos" class="form-control"></td>' +
                    '<td><a href="javascript:void(0);" class="removeRow btn btn-oval btn-danger">Quitar</a></td><tr>');
            });
            $("#pucsTable").on('click','.removeRow',function(){
                $(this).parent().parent().remove();
            });

        } );
    </script>
@endpush
