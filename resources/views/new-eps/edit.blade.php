@extends('layouts.backend.template')

@section('content')
  <div class="title-block">
    <div class="float-left">
      <h3 class="title"> Editar EPS </h3>
      <p class="title-description"> Actualizando EPS del sistema </p>
    </div>
    <div class="float-right animated fadeInRight">
      <a href="{{ route('new.eps.index') }}" class="btn btn-pill-left btn-secondary btn-lg">
        <i class="fas fa-list"></i>
        Listado
      </a>
    </div>
  </div>

  <form action="{{ route('new.eps.update', ['epsId' => $eps->id]) }}" method="POST">
    <input type="hidden" name="_method" value="PUT">
    @csrf
    <section class="section">
      <div class="row">
        <div class="col-md-6">
          <div class="card">
            <div class="card-block">
              @include('new-eps.fields')
            </div>
          </div>
        </div>
        <div class="col-md-6">
          <div class="card">
            <div class="card-block">
              @include('partials._address', ['address' => $eps->address])
              @include('partials._phone', ['phone' => $eps])
            </div>
          </div>
        </div>
        <div class="col-md-12">
          <div class="text-center">
            <button type="submit" class="btn btn-oval btn-warning">Actualizar</button>
          </div>
        </div>
      </div>
    </section>
  </form>
@endsection
