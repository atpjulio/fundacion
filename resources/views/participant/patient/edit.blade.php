@extends('layouts.backend.template')

@section('content')
  <div class="title-block">
    <div class="float-left">
      <h3 class="title"> Editar Paciente </h3>
      <p class="title-description"> Actualizando paciente del sistema </p>
    </div>
    <div class="float-right animated fadeInRight">
      <a href="{{ route('new.patient.index') }}" class="btn btn-pill-left btn-secondary btn-lg">
        <i class="fas fa-list"></i>
        Listado
      </a>
    </div>
  </div>

  <form action="{{ route('new.patient.update', ['patientId' => $patient->id]) }}" method="POST">
    <input type="hidden" name="_method" value="PUT">        
    @csrf
    <section class="section">
      <div class="row">
        <div class="col-md-6">
          <div class="card">
            <div class="card-block">
              @include('participant.patient.fields')
            </div>
          </div>
        </div>
        <div class="col-md-6">
          <div class="card">
            <div class="card-block">
              @include('partials._address-patients', ['address' => $patient->data])
              @include('participant.patient.fields2')
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
