@extends('layouts.backend.template')

@section('content')
  <div class="title-block">
    <div class="float-left">
      <h3 class="title"> Nueva EPS </h3>
      <p class="title-description"> Añadiendo nueva EPS al sistema </p>
    </div>
    <div class="float-right animated fadeInRight">
      <a href="{{ route('new.eps.index') }}" class="btn btn-pill-left btn-secondary btn-lg">
        <i class="fas fa-list"></i>
        Listado
      </a>
    </div>
  </div>

  <form action="{{ route('new.eps.store') }}" method="POST">
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
