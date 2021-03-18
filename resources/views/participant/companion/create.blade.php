@extends('layouts.backend.template')

@section('content')
  <div class="title-block">
    <div class="float-left">
      <h3 class="title"> Nuevo Acompañante </h3>
      <p class="title-description"> Añadiendo nuevo acompañante </p>
    </div>
    <div class="float-right animated fadeInRight">
      <a href="{{ route('companion.index') }}" class="btn btn-pill-left btn-secondary btn-lg">
        <i class="fas fa-list"></i>
        Listado
      </a>
    </div>
  </div>

  <section class="section">
    <div class="row justify-content-center">
      <div class="col-md-6">
        <form action="{{ route('companion.store') }}" method="POST">
          @csrf
          <div class="card">
            <div class="card-block">
              @include('participant.companion.fields')
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
