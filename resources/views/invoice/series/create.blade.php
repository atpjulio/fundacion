@extends('layouts.backend.template')

@section('content')
  <div class="title-block">
    <div class="float-left">
      <h3 class="title"> Nueva Serie de Facturas </h3>
      <p class="title-description"> Añadiendo nueva serie para la empresa: {{ $merchant->name }} </p>
    </div>
    <div class="float-right animated fadeInRight">
      <a href="{{ route('invoice.serie.index', ['merchantId' => $merchant->id]) }}"
        class="btn btn-pill-left btn-secondary btn-lg">
        <i class="fas fa-list"></i>
        Listado
      </a>
    </div>
  </div>

  <section class="section">
    <div class="row justify-content-center">
      <div class="col-md-6">
        <form action="{{ route('invoice.serie.store', ['merchantId' => $merchant->id]) }}" method="POST">
          @csrf
          <div class="card">
            <div class="card-block">
              @include('invoice.series.fields')
            </div>
          </div>
          <div class="text-center">
            <button type="submit" class="btn btn-oval btn-primary">Guardar</button>
          </div>
        </form>
      </div>
    </div>
  </section>
@endsection
