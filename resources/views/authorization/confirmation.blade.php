@extends('layouts.backend.template')

@section('content')
  <div class="title-block">
    <div class="float-left">
      <h3 class="title"> Confirma Autorización </h3>
      <p class="title-description"> Confirmación de la información antes de guardar </p>
    </div>
    <div class="float-right animated fadeInRight">
      <a href="{{ route('authorization.create') }}" class="btn btn-pill-left btn-secondary btn-lg">
        <i class="fas fa-pencil-alt"></i>
        Regresar
      </a>
    </div>
  </div>

  {!! Form::open(['route' => 'authorization.store', 'method' => 'POST', 'id' => 'myForm']) !!}
  <section class="section">
    <div class="row">
      <div class="col-md-6">
        <div class="card">
          <div class="card-block">
            <div class="card-title-block">
              <h3 class="title"> Información de la Autorización </h3>
            </div>
            @include('authorization.fields')
          </div>
        </div>
      </div>
      <div class="col-md-6">
        <div class="card">
          <div class="card-block">
            <div class="card-title-block">
              <h3 class="title"> Fecha de validez </h3>
            </div>
            @include('authorization.fields2')
          </div>
        </div>
      </div>
      <div class="col-md-12" id="companionsDiv">
        <div class="card">
          <div class="card-block">
            @include('authorization.fields3')
          </div>
        </div>
      </div>
      <div class="col-12">
        <div class="card">
          <div class="card-block">
            <div class="card-title-block">
              <h3 class="title"> Usuario al que pertenece esta autorización
              </h3>
            </div>
            <input type="hidden" name="patient_id" value="{{ $patient->id }}">
            @include('patient.fields')
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
