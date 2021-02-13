@extends('layouts.backend.template')

@push('styles')
  <link rel="stylesheet" href="{{ asset('css/dataTables.bootstrap.min.css') }} ">
@endpush

@section('content')
  <div class="title-block">
    <div class="float-left">
      <h3 class="title"> Editar Recibo de Pago</h3>
      <p class="title-description"> Actualizando recibo de pago del sistema </p>
    </div>
    <div class="float-right animated fadeInRight">
      <a href="{{ route('receipt.index') }}" class="btn btn-pill-left btn-secondary btn-lg">
        <i class="fas fa-list"></i>
        Regresar
      </a>
    </div>
  </div>
  {!! Form::open(['route' => ['receipt.update', $receipt->id], 'method' => 'PUT']) !!}
  <section class="section">
    <div class="row">
      @include('accounting.receipt.fields')
      <div class="col-12">
        <div class="text-center">
          <button type="submit" class="btn btn-oval btn-warning">Actualizar el recibo</button>
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
