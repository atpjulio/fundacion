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

    {!! Form::open(['route' => 'patient.import.process', 'method' => 'POST', 'files' => true]) !!}
    <section class="section">
        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-block">
                        <div class="card-title-block">
                            <h3 class="title"> Importar Usuarios </h3>
                        </div>

                        <p>
                            Paso 1: Seleccionar el archivo
                            <br>
                            Paso 2: Subir archivo
                        </p>
                        <div class="custom-file">
                            <input type="file" class="custom-file-input" id="customFileLang" lang="es" name="excel_file">
                            <label class="custom-file-label form-control-file" for="customFileLang">Seleccionar archivo</label>
                        </div>
                        <br>
                        <br>
                        {{--
                        @include('patient.fields')
                        --}}
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-block">
                        <div class="card-title-block">
                            <h3 class="title"> Formato que acepta el sistema </h3>
                            <br>
                            <p>Por favor utiliza el siguiente formato, de otra manera no se garantiza la carga correcta de los usuarios</p>
                            <div class="text-center">
                                <a href="{{ asset('files/Formato Usuarios.xls') }}" class="btn btn-oval btn-success">
                                    <i class="fas fa-file-excel"></i>
                                    Descargar formato
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="text-center">
                    {!! Form::submit('Subir', ['class' => 'btn btn-oval btn-primary']) !!}
                </div>
            </div>
        </div>
    </section>
    {!! Form::close() !!}
@endsection

@push('scripts')
    <script language="javascript" type="text/javascript">
        $(document).ready(function() {
            $('.custom-file-input').on('change', function () {
                // console.log($(this)[0].files[0].name);
                $(this).next('.form-control-file').addClass("selected").html($(this)[0].files[0].name);
            });
        });
    </script>
@endpush
