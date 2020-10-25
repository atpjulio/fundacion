@extends('layouts.backend.template')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/dataTables.bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/select2.min.css') }}">
@endpush

@section('content')
    <div class="title-block">
        <div class="float-left">
            <h3 class="title"> Exportar Facturas </h3>
            <p class="title-description"> Exportar facturas a PDF o Excel según se necesite </p>
        </div>
        <div class="float-right animated fadeInRight">
            <a href="{{ route('invoice.index') }}" class="btn btn-pill-left btn-secondary btn-lg">
                <i class="fas fa-list"></i>
                Listado de Facturas
            </a>
        </div>
    </div>
    <section class="section">
        {!! Form::open(['route' => 'invoice.export', 'method' => 'GET']) !!}
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-block">
                        @include('invoice.export_search_fields')
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <div class="card">
                    <div class="card-block">
                        @include('invoice.export_search_results')
                    </div>
                </div>
            </div>
        </div>
        {!! Form::close() !!}
    </section>
@endsection

@push('scripts')
    <script src="{{ asset('js/select2.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            $('.multiple-select').select2();
        });

    </script>
@endpush
{{-- <script src="{{ asset('js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('js/dataTables.bootstrap.min.js') }}"></script>
<script>
    $(document).ready(function() {
        $('#eps_id').on('change', function() {
            var data = $('#eps_id').val() + '_' + $('#initial_date').val() + '_' + $('#final_date')
                .val();
            fillInvoicesByEpsAndDate(data);
        });

        $('#initial_date').on('change', function() {
            var data = $('#eps_id').val() + '_' + $('#initial_date').val() + '_' + $('#final_date')
                .val();
            fillInvoicesByEpsAndDate(data);
        });

        $('#final_date').on('change', function() {
            var data = $('#eps_id').val() + '_' + $('#initial_date').val() + '_' + $('#final_date')
                .val();
            fillInvoicesByEpsAndDate(data);
        });

        $('#eps_id').on('change', function() {
            var data = $('#eps_id').val() + '_' + $('#initial_number').val() + '_' + $('#final_number')
                .val();
            fillInvoicesByEpsAndNumber(data);
        });

        $('#initial_number').on('change', function() {
            var data = $('#eps_id').val() + '_' + $('#initial_number').val() + '_' + $('#final_number')
                .val();
            fillInvoicesByEpsAndNumber(data);
        });

        $('#final_number').on('change', function() {
            var data = $('#eps_id').val() + '_' + $('#initial_number').val() + '_' + $('#final_number')
                .val();
            fillInvoicesByEpsAndNumber(data);
        });

    });

</script>
@endpush --}}
