@extends('layouts.backend.template')

@push('styles')
@endpush

@section('content')
  <div class="title-block">
    <div class="float-left">
      <h3 class="title"> Listado de Series de Facturas</h3>
      <p class="title-description"> {{ $merchant->name }} </p>
    </div>
    <div class="float-right animated fadeInRight">
      <a href="{{ route('invoice.serie.create', ['merchantId' => $merchant->id]) }}" class="btn btn-pill-left btn-primary btn-lg">
        <i class="fa fa-plus"></i>
        Nueva Serie
      </a>
    </div>
  </div>

  <section class="section">
    <div class="row">
      <div class="col-md-12">
        <div class="card">
          <div class="card-block">
            <div class="card-title-block">
              <div class="float-left">
                <h3 class="title"> Series de factura registradas en el sistema </h3>
              </div>
            </div>
            <div id="invoice-serie-table">
              <div class="loader" role="status">
                <span class="sr-only">Cargando...</span>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
@endsection

@push('scripts')
@endpush
