@extends('layouts.backend.template')

@push('styles')
    <link rel="stylesheet" href="{{asset('css/dataTables.bootstrap.min.css')}}">
@endpush

@section('content')
    <div class="title-block">
        <div class="float-left">
            <h3 class="title"> Actualizar RIPS Generado el {{ \Carbon\Carbon::parse($rip->created_at)->format("d/m/Y") }} - {{ substr($rip->url, 12) }}</h3>
            <p class="title-description"> Actualizando RIPS del sistema </p>
        </div>
        <div class="float-right animated fadeInRight">
            <a href="{{ route('rip.index') }}" class="btn btn-pill-left btn-secondary btn-lg">
                <i class="fas fa-list"></i>
                Regresar
            </a>
        </div>
    </div>
    {!! Form::open(['route' => ['rip.update', $rip->id], 'method' => 'PUT']) !!}
    <section class="section">
        <div class="row">
            <div class="col-md-6" >
                <div class="card">
                    <div class="card-block">
                        @include('accounting.rip.fields')
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-block">
                        <div id="dynamic-invoice-amount">                            
                            @include('accounting.rip.fields2')
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-6">
                <div class="text-center">
                    {!! Form::submit('Actualizar RIPS', ['class' => 'btn btn-oval btn-warning']) !!}
                </div>
            </div>
        </div>
    </section>
    {!! Form::close() !!}
@endsection

@push('scripts')
    <script src="{{asset('js/jquery.dataTables.min.js')}}"></script>
    <script src="{{asset('js/dataTables.bootstrap.min.js')}}"></script>
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
                }
            });
            $('#eps_id').on('change', function() {
                var data = $('#eps_id').val() + '_' + $('#initial_date').val() + '_' + $('#final_date').val();
                fillInvoicesByEpsAndDate(data);
            });

            $('#initial_date').on('change', function() {
                var data = $('#eps_id').val() + '_' + $('#initial_date').val() + '_' + $('#final_date').val();
                fillInvoicesByEpsAndDate(data);
            });

            $('#final_date').on('change', function() {
                var data = $('#eps_id').val() + '_' + $('#initial_date').val() + '_' + $('#final_date').val();
                fillInvoicesByEpsAndDate(data);
            });
        } );
    </script>
@endpush
