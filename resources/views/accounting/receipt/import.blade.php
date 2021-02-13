@extends('layouts.backend.template')

@section('content')
  <div class="title-block">
    <div class="float-left">
      <h3 class="title"> Importar Recibos </h3>
      <p class="title-description"> Añadiendo uno o más recibos desde archivo TXT </p>
    </div>
    <div class="float-right animated fadeInRight">
      <a href="{{ route('receipt.index') }}" class="btn btn-pill-left btn-secondary btn-lg">
        <i class="fas fa-list"></i>
        Regresar
      </a>
    </div>
  </div>

  {!! Form::open(['route' => 'receipt.import.process', 'method' => 'POST', 'files' => true]) !!}
  <section class="section">
    <div class="row justify-content-center">
      <div class="col-8">
        <div class="card">
          <div class="card-block">
            <div class="card-title-block">
              <h3 class="title"> Importar Recibos de Facturas de EPS</h3>
            </div>
            <p>
              Paso 1: Seleccionar el archivo TXT
              <br>
              Paso 2: Subir archivo
            </p>
            <div class="custom-file">
              <input type="file" class="custom-file-input" id="customFileLang" lang="es" name="txt_file">
              <label class="custom-file-label form-control-file" for="customFileLang">Seleccionar archivo</label>
            </div>
            <br>
            <br>
          </div>
        </div>
      </div>
      <div class="col-md-12">
        <div class="text-center">
          <button type="submit" class="btn btn-oval btn-primary">Subir</button>
        </div>
      </div>
    </div>
  </section>
  </form>
@endsection

@push('scripts')
  <script language="javascript" type="text/javascript">
    $(document).ready(function() {
      $('.custom-file-input').on('change', function() {
        $(this).next('.form-control-file').addClass("selected").html($(this)[0].files[0].name);
      });
    });

  </script>
@endpush
