@extends('layouts.backend.template')

@push('styles')
  <link rel="stylesheet" href="{{ asset('css/dataTables.bootstrap.min.css') }}">
@endpush

@section('content')
  <div class="title-block">
    <div class="float-left">
      <h3 class="title"> Actualizar Autorización </h3>
      <p class="title-description"> Actualizando una autorización del sistema </p>
    </div>
    <div class="float-right animated fadeInRight">
      <a href="{{ $authorization->invoice_id > 0 ? route('authorization.index') : route('authorization.open') }}"
        class="btn btn-pill-left btn-secondary btn-lg">
        <i class="fas fa-list"></i>
        Regresar
      </a>
    </div>
  </div>

  <section class="section">
    <div class="row">
      <div class="col-12">
        <div class="card">
          <div class="card-block">
            <div class="card-title-block">
              <div class="float-left">
                <h3 class="title"> Usuario al que pertenece esta autorización</h3>
              </div>
              <div class="float-right">
                <div class="dataTables_filter float-right form-inline mb-3 mt-0">
                  <label class="mr-2">Buscar:</label>
                  <input type="search" class="form-control form-control-sm" placeholder="" id="searching"
                    value="{{ old('searching', $authorization->patient->dni) }}" name="searching">
                </div>

              </div>
            </div>
            <div class="col-12" id="dynamic-patients">
              @include('partials._eps_patients_edit')
            </div>
          </div>
        </div>
      </div>
    </div>
    <form method="POST" action="{{ route('authorization.update', ['id' => $authorization->id]) }}" id="myForm">
      @csrf
      <input type="hidden" name="_method" value="PUT">
      <input type="hidden" name="id" value="{{ $authorization->id }}">
      <div class="row">
        <div class="col-md-12">
          <div class="card">
            <div class="card-block">
              <div class="card-title-block">
                <h3 class="title" id="authFields"> Paciente seleccionado </h3>
              </div>
              <h2 id="selected_patient">{!! $authorization->patient->full_name !!}</h2>
            </div>
          </div>
        </div>
        <div class="col-md-6">
          <div class="card">
            <div class="card-block">
              <div class="card-title-block">
                <h3 class="title" id="authFields"> Información de la Autorización </h3>
              </div>
              @include('authorization.fields')
            </div>
          </div>
        </div>
        <div class="col-md-6">
          <div class="card">
            <div class="card-block">
              <div class="card-title-block">
                <h3 class="title"> Datos del paciente </h3>
              </div>
              @include('authorization.fields2')
            </div>
          </div>
        </div>
        <div class="col-md-12">
          @include('authorization.additionals')
        </div>
        <div class="col-md-12">
          <div class="text-center">
            <button type="submit" class="btn btn-oval btn-warning">Actualizar</button>
          </div>
        </div>
      </div>
      <input type="hidden" name="patient_id" value="{{ $authorization->patient->id }}" id="patient_id">
    </form>
  </section>
@endsection

@push('scripts')
  <script src="{{ asset('js/jquery.dataTables.min.js') }}"></script>
  <script src="{{ asset('js/dataTables.bootstrap.min.js') }}"></script>
  <script src="{{ asset('js/authorization/index.js') . '?version=' . config('constants.stylesVersion') }}"></script>
@endpush
