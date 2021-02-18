@extends('layouts.backend.template')

@section('content')
  <div class="title-block">
    <div class="float-left">
      <h3 class="title"> Actualizar Usuario </h3>
      <p class="title-description"> Actualizando usuario del sistema </p>
    </div>
    <div class="float-right animated fadeInRight">
      <a href="{{ route('patient.index') }}" class="btn btn-pill-left btn-secondary btn-lg">
        <i class="fas fa-list"></i>
        Regresar
      </a>
    </div>
  </div>

  <form method="POST" action="{{ route('patient.update', ['id' => $patient->id]) }}">
    @csrf
    <input type="hidden" name="_method" value="PATCH">
    <section class="section">
      <div class="row">
        <div class="col-md-6">
          <div class="card">
            <div class="card-block">
              @include('patient.fields')
            </div>
          </div>
        </div>
        <div class="col-md-6">
          <div class="card">
            <div class="card-block">
              @include('patient.fields2')
            </div>
          </div>
        </div>
        <input type="hidden" name="id" value="{{ $patient->id }}">
        <div class="col-md-12">
          <div class="text-center">
            <button type="submit" class="btn btn-oval btn-warning">Actualizar</button>
          </div>
        </div>
      </div>
    </section>
  </form>
@endsection

@push('scripts')
  <script src="{{ asset('js/patient/edit.js') }}"></script>
@endpush
