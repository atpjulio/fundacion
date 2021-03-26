@extends('layouts.backend.template')

@section('content')
  <div class="title-block">
    <div class="float-left">
      <h3 class="title"> Editar Autorización </h3>
      <p class="title-description"> Actualizando autorización del sistema </p>
    </div>
    <div class="float-right animated fadeInRight">
      <a href="{{ route('new.authorization.index') }}" class="btn btn-pill-left btn-primary btn-lg">
        <i class="fas fa-list"></i>
        Listado
      </a>
    </div>
  </div>

  <section class="section">
    <section class="section">
      <div id="authorization-form">
        <div class="loader" role="status">
          <span class="sr-only">Cargando...</span>
        </div>
      </div>
    </section>  
  </section>
@endsection

