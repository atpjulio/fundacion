@extends('layouts.backend.template')

@push('styles')
  <link rel="stylesheet" href="{{ asset('css/dataTables.bootstrap.min.css') }}">
  <style>
    @media only screen and (min-width: 576px) {
      .modal-dialog {
        max-width: 70% !important;
        margin: 1.75rem auto;
      }
    }

  </style>
@endpush
@section('content')
  <div class="title-block">
    <div class="float-left">
      <h3 class="title"> Nueva Factura #{{ $lastNumber > 0 ? $lastNumber + 1 : 1 }}</h3>
      <p class="title-description"> Añadiendo nueva factura al sistema </p>
    </div>
    <div class="float-right animated fadeInRight">
      <a href="{{ route('invoice.index') }}" class="btn btn-pill-left btn-secondary btn-lg">
        <i class="fas fa-list"></i>
        Listado
      </a>
    </div>
  </div>
  {!! Form::open(['route' => 'invoice.store', 'method' => 'POST']) !!}
  <section class="section">
    <invoice-component :companies="'{{ json_encode($companies) }}'"
      :number={{ $lastNumber > 0 ? $lastNumber + 1 : 1 }}
      :codes="'{{ json_encode(old('multiple_codes', isset($invoice) ? $invoice->multiple_codes : [])) }}'"
      :days="'{{ json_encode(old('multiple_days', isset($invoice) ? $invoice->multiple_days : [])) }}'"
      :totals="'{{ json_encode(old('multiple_totals', isset($invoice) ? $invoice->multiple_totals : [])) }}'">
    </invoice-component>
    <div class="row">
      <div class="col-12">
        <div class="text-center">
          <button type="submit" class="btn btn-oval btn-primary">Guardar la factura</button>
        </div>
      </div>
    </div>
  </section>
  <input type="hidden" name="selected_price" value="0" id="selected_price">
  </form>
@endsection

@push('scripts')
  <script src="{{ asset('js/jquery.dataTables.min.js') }}"></script>
  <script src="{{ asset('js/dataTables.bootstrap.min.js') }}"></script>
  <script src="{{ asset('js/invoice/create.js') . '?version=' . config('constants.stylesVersion') }}"></script>
@endpush
