@extends('layouts.backend.template')

@push('styles')
    <link rel="stylesheet" href="{{asset('css/dataTables.bootstrap.min.css')}}">
@endpush

@section('content')
    <div class="title-block">
        <div class="float-left">
            <h3 class="title"> Actualizar Recibo de Pago </h3>
            <p class="title-description"> Actualizando recibo de pago del sistema </p>
        </div>
        <div class="float-right animated fadeInRight">
            <a href="{{ route('receipt.index') }}" class="btn btn-pill-left btn-secondary btn-lg">
                <i class="fas fa-list"></i>
                Regresar
            </a>
        </div>
    </div>
    {!! Form::open(['route' => ['receipt.update', $receipt->id], 'method' => 'PUT']) !!}
    <section class="section">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-block">
                        <div class="card-title-block">
                            <h3 class="title"> Selecciona la factura para este recibo de pago </h3>
                        </div>
                        <div class="col-12">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-condensed table-hover" id="myTable">
                                    <thead>
                                    <th># Factura</th>
                                    <th>Autorización</th>
                                    <th>Monto</th>
                                    <th>Pagado</th>
                                    <th>Restante</th>
                                    <th>Opciones</th>
                                    </thead>
                                    <tbody>
                                    @foreach($invoices as $invoice)
                                        <tr>
                                            <td>{!! sprintf("%05d", $invoice->number) !!}</td>
                                            <td>{!! $invoice->authorization_code !!}</td>
                                            <td>$ {!! number_format($invoice->total, 2, ",", ".") !!}</td>
                                            <td>$ {!! number_format($invoice->payment, 2, ",", ".") !!}</td>
                                            <td>$ {!! number_format($invoice->total - $invoice->payment, 2, ",", ".") !!}</td>
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
                        @include('accounting.receipt.fields')
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-block">
                        @include('accounting.receipt.fields2')
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <div class="card">
                    <div class="card-block">
                        @include('accounting.receipt.fields3')
                    </div>
                </div>
            </div>
            <div class="col-12">
                <div class="text-center">
                    {!! Form::submit('Actualizar el recibo', ['class' => 'btn btn-oval btn-warning']) !!}
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
                    },
                    "oSearch": {"sSearch": "{{ $receipt->invoice->number }}"}                    
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
            $('#pucs').on('change', function (e) {
                $('#puc_code').val($('#pucs').val());
                var fullDescription = $("#pucs").children("option").filter(":selected").text().split('-');
                var description = '';
                var counter = 0;
                
                fullDescription.forEach(function(element) {
                    if (counter > 0) {
                        description += element.trim();
                    }
                    counter++;
                });

                $('#puc_description').val(description);                
            });
            $(".addRow").click(function(){
                $("#pucsTable").append('<tr>' +
                    '<td><input type="text" id="notePucs" name="notePucs[]" value="' + $('#puc_code').val() + '" class="form-control" placeholder="Código PUC"/></td>' + 
                    '<td><input type="text" name="pucDescription[]" value="' + $('#puc_description').val()+ '" placeholder="Descripción" class="form-control"></td>' +
                    '<td><input type="text" name="pucDebit[]" value="' + $('#puc_debit').val() + '" placeholder="Débitos" class="form-control"></td>' +
                    '<td><input type="text" name="pucCredit[]" value="' + $('#puc_credit').val() + '"placeholder="Créditos" class="form-control"></td>' +
                    '<td><a href="javascript:void(0);" class="removeRow btn btn-oval btn-danger">Quitar</a></td><tr>');
                $('#puc_code').val('');
                $('#puc_description').val('');
                $('#puc_debit').val('');
                $('#puc_credit').val('');
            });
            $("#pucsTable").on('click','.removeRow',function(){
                $(this).parent().parent().remove();
            });

        } );
    </script>
@endpush
