@extends('layouts.backend.template')

@section('content')
  <div class="title-block">
    <div class="float-left">
      <h3 class="title"> Nuevo Servicio </h3>
      <p class="title-description"> Añadiendo nuevo servicio para la EPS: {{ $eps->name }} </p>
    </div>
    <div class="float-right animated fadeInRight">
      <a href="{{ route('new.eps.service.index', ['epsId' => $eps->id]) }}"
        class="btn btn-pill-left btn-secondary btn-lg">
        <i class="fas fa-list"></i>
        Listado
      </a>
    </div>
  </div>

  <section class="section">
    <div class="row justify-content-center">
      <div class="col-md-6">
        <form action="{{ route('new.eps.service.store', ['epsId' => $eps->id]) }}" method="POST">
          @csrf
          <div class="card">
            <div class="card-block">
              @include('new-eps.services.fields')
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
