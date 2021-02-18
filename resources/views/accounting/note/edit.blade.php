@extends('layouts.backend.template')

@push('styles')
  <link rel="stylesheet" href="{{ asset('css/dataTables.bootstrap.min.css') }}">
@endpush

@section('content')
  <div class="title-block">
    <div class="float-left">
      <h3 class="title"> Editar Nota Interna </h3>
      <p class="title-description"> Actualizando nota interna del sistema </p>
    </div>
    <div class="float-right animated fadeInRight">
      <a href="{{ route('accounting-note.index') }}" class="btn btn-pill-left btn-secondary btn-lg">
        <i class="fas fa-list"></i>
        Regresar
      </a>
    </div>
  </div>
  <form method="POST" action="{{ route('accounting-note.update', ['id' => $note->id]) }}">
    @csrf
    <input type="hidden" name="_method" value="PUT">
    <section class="section">
      <div class="row">
        @include('accounting.note.fields')
        <div class="col-12 text-center">
          <button type="submit" class="btn btn-oval btn-warning">Actualizar la nota</button>
        </div>
      </div>
    </section>
    <input type="hidden" name="invoice_id" value="" id="invoice_id">
  </form>
@endsection

@push('scripts')
  <script src="{{ asset('js/jquery.dataTables.min.js') }}"></script>
  <script src="{{ asset('js/dataTables.bootstrap.min.js') }}"></script>
  <script src="{{ asset('js/accounting/egress/create.js') }}"></script>
@endpush
