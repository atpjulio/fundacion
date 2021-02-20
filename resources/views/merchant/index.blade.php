@extends('layouts.backend.template')

@push('styles')
@endpush

@section('content')
  <div class="title-block">
    <div class="float-left">
      <h3 class="title"> Listado de Empresas</h3>
      <p class="title-description"> Listado </p>
    </div>
    <div class="float-right animated fadeInRight">
      <a href="{{ route('merchant.create') }}" class="btn btn-pill-left btn-primary btn-lg">
        <i class="fa fa-plus"></i>
        Nueva Empresa
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
                <h3 class="title"> Empresas registradas en el sistema </h3>
              </div>
            </div>
            <div id="merchant-table">
              <div class="spinner-border" role="status">
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
