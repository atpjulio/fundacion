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
    <form action="{{ route('invoice.export') }}" method="GET" target="_blank">
      @csrf
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
    </form>
  </section>
@endsection

@push('scripts')
  <script src="{{ asset('js/select2.min.js') }}"></script>
  <script>
    $(document).ready(function() {
      $('.multiple-select').select2();
    });

    function processExport() {
      const updateQueryParams = window.location.search.replace('export_date={{ now()->format('Y-m-d') }}',
        'export_date=' + $('#export-date').val());
      window.open('{{ env('APP_URL') }}/invoices/exports' + updateQueryParams + '&export=' + $('#export-method').val(),
        '_blank');
    }

  </script>
@endpush
