@extends('layouts.backend.template')

@section('content')
  {{-- dd($eps) --}}
  <div class="title-block">
    <div class="float-left">
      <h3 class="title"> Editar Servicio para: {!! $eps->alias ? $eps->alias : $eps->name !!} </h3>
      <p class="title-description"> Mostrando / actualizando servicio de EPS </p>
    </div>
    <div class="float-right animated fadeInRight">
      <a href="{{ route('eps.services.index', $eps->id) }}" class="btn btn-pill-left btn-secondary btn-lg">
        <i class="fas fa-list"></i>
        Regresar
      </a>
    </div>
  </div>

  <section class="section">
    <div class="row justify-content-center">
      <div class="col-md-6">
        <form method="POST" action="{{ route('eps.services.update', ['id' => $service->id]) }}">
          @csrf
          <input type="hidden" name="_method" value="PATCH">
          <div class="card">
            <div class="card-block">
              @include('eps.services.fields')
            </div>
          </div>
          <div class="text-center">
            <input type="hidden" name="id" value="{{ $service->id }}">
            <input type="hidden" name="eps_id" value="{{ $eps->id }}">
            <button type="submit" class="btn btn-oval btn-warning">Actualizar</button>
          </div>
        </form>
      </div>
    </div>
  </section>
@endsection
