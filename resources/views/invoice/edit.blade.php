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
                // console.log($(this).parent().parent().find('td')[5].outerText);
                $('#total_days').val($(this).parent().parent().find('td')[5].outerText);
                $('#selected_price').val($(this).parent().find('input')[0].value);
                $('#total').val($('#selected_price').val() * $('#total_days').val());
                $('#authorization_code').val($(this).parent().parent().find('td').first()[0].outerText);
                $('html, body').animate({
                    scrollTop: $('#beginning').offset().top
                }, 300, function(){
                    window.location.href = '#beginning';
                });
            });
            $('#total_days').on('change', function (e) {
                $('#total').val($('#selected_price').val() * $('#total_days').val());
            });
        } );
    </script>
@endpush
