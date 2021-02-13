@extends('layouts.backend.template')

@section('content')
  <div class="title-block">
    <div class="float-left">
      <h3 class="title"> Mi perfil </h3>
      <p class="title-description"> Espacio para modificar mis datos personales y contraseña </p>
    </div>
    <div class="float-right animated fadeInRight">
      <a href="{{ route('home') }}" class="btn btn-pill-left btn-secondary btn-lg">
        <i class="fa fa-home"></i>
        Inicio
      </a>
    </div>
  </div>

  {!! Form::open(['route' => ['user.profile.update', $user->id], 'method' => 'PUT', 'files' => true]) !!}
  <section class="section">
    <div class="row">
      <div class="col-md-6">
        <div class="card">
          <div class="card-block">
            @include('user.fields')
          </div>
        </div>
      </div>
      <div class="col-md-6">
        {{-- <div class="card">
                    <div class="card-block">
                        @include('user.fields2')
                    </div>
                </div> --}}
      </div>
      <div class="col-md-6 text-center">
        <button type="submit" class="btn btn-oval btn-warning">Actualizar mi perfil</button>
      </div>
    </div>
  </section>
  </form>
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
