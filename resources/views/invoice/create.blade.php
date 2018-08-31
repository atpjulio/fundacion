@extends('layouts.backend.template')

@push('styles')
    <link rel="stylesheet" href="{{asset('css/dataTables.bootstrap.min.css')}}">
@endpush

@section('content')
    <div class="title-block">
        <div class="float-left">
            <h3 id="beginning" class="title"> Nueva Factura </h3>
            <p class="title-description"> Añadiendo nueva factura al sistema </p>
        </div>
        <div class="float-right animated fadeInRight">
            <a href="{{ route('invoice.index') }}" class="btn btn-pill-left btn-secondary btn-lg">
                <i class="fas fa-list"></i>
                Regresar
            </a>
        </div>
    </div>
    {!! Form::open(['route' => 'invoice.store', 'method' => 'POST']) !!}
    <section class="section">
        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-block">
                        @include('invoice.fields')
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-block">
                        @include('invoice.fields2')
                    </div>
                </div>
            </div>
            <div class="col-12">
                <div class="text-right">
                    {!! Form::submit('Guardar la factura', ['class' => 'btn btn-oval btn-primary']) !!}
                </div>
            </div>
            <div class="col-12">
                <div class="card">
                    <div class="card-block">
                        <div class="card-title-block">
                            <h3 class="title"> Autorización para esta factura</h3>
                        </div>
                        <div class="col-12">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-condensed table-hover" id="myTable">
                                    <thead>
                                    <th>Código</th>
                                    <th>EPS</th>
                                    <th>Usuario</th>
                                    <th>Desde</th>
                                    <th>Hasta</th>
                                    <th>Total Días</th>
                                    <th>Opciones</th>
                                    </thead>
                                    <tbody>
                                    @foreach($authorizations as $key => $authorization)
                                        <tr>
                                            <td>{!! $authorization->code !!}</td>
                                            <td>{!! $authorization->eps->alias ? $authorization->eps->alias : $authorization->eps->name !!}</td>
                                            <td>{!! $authorization->patient->full_name !!}</td>
                                            <td>{!! \Carbon\Carbon::parse($authorization->date_from)->format("d/m/Y") !!}</td>
                                            <td>{!! \Carbon\Carbon::parse($authorization->date_to)->format("d/m/Y") !!}</td>
                                            <td>{!! \Carbon\Carbon::parse($authorization->date_to)->diffInDays(\Carbon\Carbon::parse($authorization->date_from)) !!}</td>
                                            <td>
                                                {!! Form::button('Seleccionar', ['class' => 'btn btn-oval btn-success', 'id' => 'button'.$key ]) !!}
                                                {!! Form::hidden('daily_price', $authorization->eps->daily_price, ['id' => 'daily_price']) !!}
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
        </div>
    </section>
    {!! Form::hidden('selected_price', 0, ['id' => 'selected_price']) !!}
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
                $('#total_days').val($(this).parent().parent().find('td')[5].outerText);
                $('#selected_price').val($(this).parent().find('input')[0].value);
                $('#total').val($('#selected_price').val() * $('#total_days').val());
                $('#authorization_code').val($(this).parent().parent().find('td').first()[0].outerText);
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

        } );
    </script>
@endpush
