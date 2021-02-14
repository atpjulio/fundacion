@extends('layouts.backend.template')

@section('content')
  {{-- dd($eps) --}}
  <div class="title-block">
    <div class="float-left">
      <h3 class="title"> Añadir Servicio para: {!! $eps->alias ? $eps->alias : $eps->name !!} </h3>
      <p class="title-description"> Añadiendo un nuevo servicio al sistema </p>
    </div>
    <div class="float-right animated fadeInRight">
      <a href="{{ URL::previous() }}" class="btn btn-pill-left btn-secondary btn-lg">
        <i class="fas fa-list"></i>
        Regresar
      </a>
    </div>
  </div>

  <section class="section">
    <div class="row justify-content-center">
      <div class="col-md-6">
        <form action="{{ route('eps.services.store') }}" method="POST">
          @csrf
          <div class="card">
            <div class="card-block">
              @include('eps.services.fields')
            </div>
          </div>
          <div class="text-center">
            <input type="hidden" name="eps_id" value="{{ $eps->id }}">
            <button type="submit" class="btn btn-oval btn-primary">Guardar</button>
          </div>
        </form>
      </div>
    </div>
  </section>
@endsection
