@extends('layouts.backend.template')

@push('styles')
    <link rel="stylesheet" href="{{asset('css/dataTables.bootstrap.min.css')}}">
@endpush

@section('content')
    <div class="title-block">
        <div class="float-left">
            <h3 id="beginning" class="title"> Editar Factura </h3>
            <p class="title-description"> Editando factura del sistema </p>
        </div>
        <div class="float-right animated fadeInRight">
            <a href="{{ route('invoice.index') }}" class="btn btn-pill-left btn-secondary btn-lg">
                <i class="fas fa-list"></i>
                Regresar
            </a>
        </div>
    </div>
    {!! Form::open(['route' => ['invoice.update', $invoice->id], 'method' => 'PUT']) !!}
    <section class="section">
        <div class="row">
            @include('invoice.fields')
            <div class="col-12">
                <div class="text-center">
                    {!! Form::submit('Actualizar la factura', ['class' => 'btn btn-oval btn-warning']) !!}
                </div>
            </div>
        </div>
    </section>
    {!! Form::hidden('selected_price', $invoice->eps->daily_price, ['id' => 'selected_price']) !!}
    {!! Form::hidden('id', $invoice->id) !!}
    {!! Form::close() !!}
@endsection

@push('scripts')
    <script src="{{ asset('js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('js/dataTables.bootstrap.min.js') }}"></script>
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
                },
                "oSearch": {"sSearch": "{{ $invoice->authorization_code }}"}
            });
            $('#myTable').on('click','.btn-success', function() {
                days = parseInt($(this).parent().parent().find('td')[5].outerText);
                if ($('#multiple').is(":checked")) {
                    console.log($("#multiple_table tr").length);
                    console.log($("#multiple_table tr:nth-child(1)").find('td input')[0].value.length);
                    if ($("#multiple_table tr:nth-child(1)").find('td input')[0].value.length > 0) {
                        $("#multiple_table").append(
                            '<tr><td><input type="text" id="multiple_codes" name="multiple_codes[]" value="' + $(this).parent().parent().find('td').first()[0].outerText.trim() + '" class="form-control" placeholder="Número de autorización" />'
                            + '</td><td><input type="number" id="multiple_days" name="multiple_days[]" value="' + days + '" class="form-control multipleDays" placeholder="Total de días" min="0"/>'
                            + '</td><td><input type="number" id="multiple_totals" name="multiple_totals[]" value="' + ($(this).parent().find('input')[0].value * days) + '" class="form-control" placeholder="Valor total" min="0"/>'
                            + '</td><td><a href="javascript:void(0);" class="removeRow btn btn-oval btn-danger">Quitar</a></td></tr>'
                        );
                    } else {
                        $("#multiple_table tr:last").remove();
                        $("#multiple_table").append(
                            '<tr><td><input type="text" id="multiple_codes" name="multiple_codes[]" value="' + $(this).parent().parent().find('td').first()[0].outerText.trim() + '" class="form-control" placeholder="Número de autorizaciónnnnn" />'
                            + '</td><td><input type="number" id="multiple_days" name="multiple_days[]" value="' + days + '" class="form-control multipleDays" placeholder="Total de días" min="0"/>'
                            + '</td><td><input type="number" id="multiple_totals" name="multiple_totals[]" value="' + ($(this).parent().find('input')[0].value * days) + '" class="form-control" placeholder="Valor total" min="0"/>'
                            + '</td><td><a href="javascript:void(0);" class="addRow btn btn-oval btn-success">Añadir</a></td></tr>'
                        );
                    }

                    $('#alertTable').css('display', 'none');
                    $('#tableMessage').html('');              

                    $('html, body').animate({
                        scrollTop: $('#multiple_table').offset().top
                    }, 300, function(){
                        window.location.href = '#multiple_table';
                    });
                } else {
                    $('#total_days').val(days);
                    $('#selected_price').val($(this).parent().find('input')[0].value);
                    $('#total').val($('#selected_price').val() * $('#total_days').val());
                    //$('#total').val($('#selected_price').val() * $('#total_days').val() + parseInt($('#total').val()));
                    $('#authorization_code').val($(this).parent().parent().find('td').first()[0].outerText.trim());
                    //$('#authorization_code').val($(this).parent().parent().find('td').first()[0].outerText.trim() + "," + $('#authorization_code').val());

                    $('html, body').animate({
                        scrollTop: $('#beginning').offset().top
                    }, 300, function(){
                        window.location.href = '#beginning';
                    });            
                }
            });
            $('#total_days').on('change', function (e) {
                $('#total').val($('#selected_price').val() * $('#total_days').val());
            });
            $('#multiple').on('change', function (e) {
                if ($('#multiple').is(":checked")) {
                    $('#multiple').val("1");
                    $('#multiple_card').css('display', 'block');
                    $('#multiple_card').addClass('animated fadeIn');
                    $('#authorization_code').val('');
                    $('#total_days').val('');
                    $('#total').val('');
                } else {
                    $('#multiple_card').css('display', 'none');
                    $('#multiple').val("0");
                }
            });
            $('#multiple_table').on('change', '.multipleDays', function (e) {
                $(this).parent().parent().find('td input')[2].value = e.target.value * $('#selected_price').val();        
            });
            $("#multiple_table").on('click','.addRow', function() {
                if ($('#multiple_codes').val().length > 0 && $('#multiple_days').val() > 0 && $('#multiple_totals').val() > 0) {
                    $("#multiple_table").append(
                        '<tr><td><input type="text" id="multiple_codes" name="multiple_codes[]" class="form-control" placeholder="Número de autorización" value=""/>'
                        + '</td><td><input type="number" id="multiple_days" name="multiple_days[]" class="form-control multipleDays" placeholder="Total de días" min="0" value=""/>'
                        + '</td><td><input type="number" id="multiple_totals" name="multiple_totals[]" class="form-control" placeholder="Valor total" min="0" value=""/>'
                        + '</td><td><a href="javascript:void(0);" class="removeRow btn btn-oval btn-danger">Quitar</a></td></tr>'
                    );
                    $('#alertTable').css('display', 'none');
                    $('#tableMessage').html('');              
                } else {
                    if ($('#multiple_codes').val().length == 0) {
                        $('#tableMessage').html('Número de autorización inválido');              
                        $('#alertTable').css('display', 'block');
                    } else if ($('#multiple_days').val().length == 0) {
                        $('#tableMessage').html('Número de días inválido');              
                        $('#alertTable').css('display', 'block');                        
                    } else if ($('#multiple_totals').val().length == 0) {
                        $('#tableMessage').html('Monto de factura inválido');              
                        $('#alertTable').css('display', 'block');                        
                    }
                }

            });
            $("#multiple_table").on('click','.removeRow', function() {
                $(this).parent().parent().remove();
            });
        } );
    </script>
@endpush
