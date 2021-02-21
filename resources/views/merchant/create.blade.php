@extends('layouts.backend.template')

@push('styles')
  {{-- <link rel="stylesheet" href="{{ asset('css/dataTables.bootstrap.min.css') }}">
  <style>
    @media only screen and (min-width: 576px) {
      .modal-dialog {
        max-width: 70% !important;
        margin: 1.75rem auto;
      }
    }

  </style> --}}
@endpush

@section('content')
  <div class="title-block">
    <div class="float-left">
      <h3 class="title"> Nueva Empresa </h3>
      <p class="title-description"> Añadiendo nueva empresa al sistema </p>
    </div>
    <div class="float-right animated fadeInRight">
      <a href="{{ route('merchant.index') }}" class="btn btn-pill-left btn-secondary btn-lg">
        <i class="fas fa-list"></i>
        Listado
      </a>
    </div>
  </div>

  <form action="{{ route('merchant.store') }}" method="POST">
    @csrf
    <section class="section">
      <div class="row">
        <div class="col-md-6">
          <div class="card">
            <div class="card-block">
              @include('merchant.fields')
            </div>
          </div>
        </div>
        <div class="col-md-6">
          <div class="card">
            <div class="card-block">
              @include('partials._address')
              @include('partials._phone')
            </div>
          </div>
        </div>
        <div class="col-md-12">
          <div class="text-center">
            <button type="submit" class="btn btn-oval btn-primary">Guardar</button>
          </div>
        </div>
      </div>
    </section>
  </form>
@endsection

@push('scripts')
@endpush
