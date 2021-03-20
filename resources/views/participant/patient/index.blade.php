@extends('layouts.backend.template')

@section('content')
  <div class="title-block">
    <div class="float-left">
      <h3 class="title"> Listado de Pacientes</h3>
      <p class="title-description"> Listado de pacientes registrados en el sistema </p>
    </div>
    <div class="float-right animated fadeInRight">
      <a href="{{ route('new.patient.create') }}" class="btn btn-pill-left btn-primary btn-lg">
        <i class="fa fa-plus"></i>
        Nuevo Paciente
      </a>
    </div>
  </div>

  <section class="section">
    <div class="row">
      <div class="col-md-12">
        <div class="card">
          <div class="card-block">
            <div class="card-title-block">
              <div class="float-left">
                <h3 class="title"> Pacientes registrados en el sistema </h3>
              </div>
            </div>
            <div id="patient-table">
              <div class="loader" role="status">
                <span class="sr-only">Cargando...</span>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
@endsection