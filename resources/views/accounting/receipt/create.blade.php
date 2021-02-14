@extends('layouts.backend.template')

@push('styles')
  <link rel="stylesheet" href="{{ asset('css/dataTables.bootstrap.min.css') }} ">
@endpush

@section('content')
  <div class="title-block">
    <div class="float-left">
      <h3 class="title"> Nuevo Recibo de Pago</h3>
      <p class="title-description"> Añadiendo nuevo recibo de pago al sistema </p>
    </div>
    <div class="float-right animated fadeInRight">
      <a href="{{ route('receipt.index') }}" class="btn btn-pill-left btn-secondary btn-lg">
        <i class="fas fa-list"></i>
        Regresar
      </a>
    </div>
  </div>
  <form action="{{ route('receipt.store') }}" method="POST">
    @csrf
    <section class="section">
      <div class="row">
        @include('accounting.receipt.fields')
        <div class="col-12">
          <div class="text-center">
            <button type="submit" class="btn btn-oval btn-primary">Guarda el recibo</button>
          </div>
        </div>
      </div>
    </section>
  </form>
@endsection

@push('scripts')
  <script src="{{ asset('js/jquery.dataTables.min.js') }}"></script>
  <script src="{{ asset('js/dataTables.bootstrap.min.js') }}"></script>
  <script src="{{ asset('js/accounting/egress/create.js') }}"></script>
@endpush
