@extends('layouts.backend.template')

@push('styles')
    <link rel="stylesheet" href="{{asset('css/dataTables.bootstrap.min.css')}}">
@endpush

@section('content')
    <div class="title-block">
        <div class="float-left">
            <h3 class="title"> Volumen de Facturas </h3>
            <p class="title-description"> Generar volumen de facturas para una EPS </p>
        </div>
        <div class="float-right animated fadeInRight">
            <a href="{{ route('invoice.index') }}" class="btn btn-pill-left btn-secondary btn-lg">
                <i class="fas fa-list"></i>
                Listado
            </a>
        </div>
    </div>
    {!! Form::open(['route' => 'invoice.volume.pdf', 'method' => 'GET', 'target' => '_blank']) !!}
    <section class="section">
        <div class="row">
            <div class="col-md-6" >
                <div class="card">
                    <div class="card-block">
                        @include('invoice.relation_fields')
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-block">
                        @include('invoice.relation_fields2')
                    </div>
                </div>
            </div>
            <div class="col-6">
                <div class="text-center">
                    {!! Form::submit('Generar volumen de facturas', ['class' => 'btn btn-oval btn-primary']) !!}
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
