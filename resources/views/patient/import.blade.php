@extends('layouts.backend.template')

@section('content')
  <div class="title-block">
    <div class="float-left">
      <h3 class="title"> Importar Usuarios </h3>
      <p class="title-description"> Añadiendo uno o más usuarios desde archivo excel </p>
    </div>
    <div class="float-right animated fadeInRight">
      <a href="{{ route('patient.index') }}" class="btn btn-pill-left btn-secondary btn-lg">
        <i class="fas fa-list"></i>
        Regresar
      </a>
    </div>
  </div>

  <section class="section">
    <div class="row">
      <div class="col-md-6">
        <div class="card">
          <div class="card-block">
            <div class="card-title-block">
              <h3 class="title"> Importar Usuarios desde TXT </h3>
            </div>
            <form action="{{ route('patient.import.process.txt') }}" method="POST" files="true">
              @csrf

              <div class="form-group  @if ($errors->has('eps_code')) has-error @endif">
                <label for="eps_code" class="control-label">Seleccione EPS y luego cargue el archivo</label>
                {!! Form::select('eps_code', $epss, old('eps_code'), ['class' => 'form-control']) !!}
              </div>
              <div class="custom-file">
                <input type="file" class="custom-file-input" id="customFileLang" lang="es" name="txt_file">
                <label class="custom-file-label form-control-file" for="customFileLang">Seleccionar archivo</label>
              </div>
              <br><br>
              <div class="text-center mb-4">
                <button type="submit" class="btn btn-oval btn-primary">Subir</button>
              </div>
            </form>
          </div>
        </div>
      </div>
      <div class="col-md-6">
        <div class="card">
          <div class="card-block">
            <div class="card-title-block">
              <h3 class="title"> Importar Usuarios desde Excel </h3>
              <br>
              <p>Por favor utiliza el siguiente <a href="{{ asset('files/Formato Usuarios.xls') }}">formato</a></p>
              <p>Y al llenar el archivo, lo subes al sistema</p>
              <form action="{{ route('patient.import.process') }}" method="POST" files="true">
                @csrf
                <div class="custom-file">
                  <input type="file" class="custom-file-input" id="customFileLang" lang="es" name="excel_file">
                  <label class="custom-file-label form-control-file" for="customFileLang">Seleccionar archivo</label>
                </div>
                <br><br>
                <div class="text-center">
                  <button type="submit" class="btn btn-oval btn-primary">Subir</button>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
      <div class="col-md-12">
      </div>
    </div>
  </section>

@endsection

@push('scripts')
  <script language="javascript" type="text/javascript">
    $(document).ready(function() {
      $('.custom-file-input').on('change', function() {
        // console.log($(this)[0].files[0].name);
        $(this).next('.form-control-file').addClass("selected").html($(this)[0].files[0].name);
      });
    });

  </script>
@endpush
