@extends('layouts.backend.template')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/dataTables.bootstrap.min.css') }}">
@endpush

@section('content')
    <div class="title-block">
        <div class="float-left">
            <h3 class="title"> Añadir Autorización </h3>
            <p class="title-description"> Añadiendo una autorización nueva al sistema </p>
        </div>
        <div class="float-right animated fadeInRight">
            <a href="{{ route('authorization.index') }}" class="btn btn-pill-left btn-secondary btn-lg">
                <i class="fas fa-list"></i>
                Listado
            </a>
        </div>
    </div>

    {!! Form::open(['route' => 'authorization.store', 'method' => 'POST', 'id' => 'myForm']) !!}
    <section class="section">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-block">
                        <div class="card-title-block">
                            <h3 class="title"> Usuario al que pertenece esta autorización
                                <a href="{{ route('patient.create.authorization') }}" class="btn btn-oval btn-info float-right">Nuevo Usuario</a>
                            </h3>
                        </div>
                        <div class="col-12" id="dynamic-patients">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-condensed table-hover" id="myTable">
                                    <thead>
                                    <th>Tipo Doc.</th>
                                    <th>Documento</th>
                                    <th>Nombre Completo</th>
                                    <th>Fecha Nac.</th>
                                    <th>Edad</th>
                                    <th>Opciones</th>
                                    </thead>
                                    <tbody>
                                    @foreach($patients as $patient)
                                        <tr>
                                            <td>{!! $patient->dni_type !!}</td>
                                            <td>{!! $patient->dni !!}</td>
                                            <td>{!! $patient->full_name !!}</td>
                                            <td>{!! \Carbon\Carbon::parse($patient->birth_date)->format("d/m/Y") !!}</td>
                                            <td>{!! $patient->age !!}</td>
                                            <td>
                                                <button type="button" class="btn btn-oval btn-primary btn-sm" onclick="sendInfo({{ $patient->id }}, {{ $patient->eps_id }})">
                                                    Seleccionar
                                                </button>
                                                {{--  

                                                <a href="#authFields" class="btn btn-oval btn-primary btn-sm">Seleccionar</a>
                                                --}}
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div id="restOfFields" style="display: none;">
            <div class="row">
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
                                <h3 class="title"> Fecha de validez </h3>
                            </div>
                            @include('authorization.fields2')
                        </div>
                    </div>
                </div>
                <div class="col-md-12" id="companionsDiv" @if (old('companion')) style="display: block;" @else style="display: none;" @endif>
                    <div class="card">
                        <div class="card-block">
                            @include('authorization.fields3')
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="text-center">
                        {!! Form::submit('Guardar', ['class' => 'btn btn-oval btn-primary']) !!}
                    </div>
                </div>                
            </div>
        </div>
    </section>
    {!! Form::hidden('patient_id', null, ['id' => 'patient_id']) !!}
    {!! Form::close() !!}
@endsection

@push('scripts')
    <script src="{{ asset('js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('js/dataTables.bootstrap.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            $('#myTable').DataTable({
                "language": {
                    "lengthMenu": "Mostrando _MENU_ registros por página",
                    "zeroRecords": "No se encontró ningún resultado",
                    "info": "Mostrando página _PAGE_ de _PAGES_",
                    "infoEmpty": "No hay información disponible",
                    "infoFiltered": "(filtrando de un total de _MAX_ registros)",
                    "search":         "Buscar:",
                    "paginate": {
                        "first":      "Primera",
                        "last":       "Última",
                        "next":       "Siguiente",
                        "previous":   "Anterior"
                    }
                }
            });
            $('#companion').on('change', function (e) {
                if ($('#companion').val() == 1) {
                    $('#companionsDiv').css('display', 'block');
                    $('#companionsDiv').addClass('animated fadeIn');
                } else {
                    $('#companionsDiv').css('display', 'none');
                }
            });
            $('#epsSelect').on('change', function (e) {
                $('#serviceLink').attr("href", "/eps-services/" + $('#epsSelect').val() + "/create-from-authorization");
                fillServices($('#epsSelect').val());
                fillPatients($('#epsSelect').val());
                fillCompanionServices($('#epsSelect').val());
            });
            $('#companion_eps_service_id').on('change', function(e) {
                if ($(this).val() > 0) {             
                    $('#companion_service').val($(this).children('option').filter(":selected").text());
                    $('#companion_service_id').val($(this).val());
                    $('#alertTable').css('display', 'none');
                    $('#tableMessage').html('');              

                } else {
                    $('#companion_service').val('');
                    $('#companion_service_id').val('');
                    $('#tableMessage').html('Por favor seleccione un servicio válido');              
                    $('#alertTable').css('display', 'block');
                }
            });
            $(".addRow").click( function() {

                if ($('#companion_service').val().length > 0 && $('#companion_service_id').val().length > 0 && $('#companion_document').val().length > 0) {
                    $("#companionsTable").append('<tr><td><input type="text" id="companionDni" name="companionDni[]" value="' + $('#companion_document').val() + '" class="form-control" placeholder="Número de Documento"/></td><td><input type="text" id="companionService" value="' + $('#companion_service').val() + '" name="companionService[]" class="form-control" placeholder="Servicio para el acompañante" readonly /><input type="hidden" name="companionServiceId[]" id="companionServiceId" value="' + $('#companion_service_id').val() + '"></td><td><a href="javascript:void(0);" class="removeRow btn btn-oval btn-danger">Quitar</a></td><tr>');

                    $('#companion_document').val('');
                    $('#companion_service').val('');
                    $('#companion_service_id').val('');                    
                    $('#alertTable').css('display', 'none');
                    $('#tableMessage').html('');              
                } else {
                    if ($('#companion_service').val().length == 0) {
                        $('#tableMessage').html('Por favor seleccione un servicio válido');              
                        $('#alertTable').css('display', 'block');

                    } else {
                        $('#tableMessage').html('Por favor ingrese un número de documento');              
                        $('#alertTable').css('display', 'block');                        
                    }
                }

            });
            $("#companionsTable").on('click','.removeRow', function() {
                $(this).parent().parent().remove();
            });

        } );
        function sendInfo(id, eps_id) {
            $('#patient_id').val(id);
            // $('#myForm').submit();
            $('#restOfFields').css('display', 'block');            
            $('#restOfFields').addClass('animated fadeIn');
            $('html, body').animate({
                    scrollTop: $('#authFields').offset().top
                }, 300, function(){
                    window.location.href = '#authFields';
                });
            console.log('EPS:' + eps_id);
            $('#epsSelect').val(eps_id);
            fillServices($('#epsSelect').val());
            fillPatients($('#epsSelect').val());
            fillCompanionServices($('#epsSelect').val());
        }
    </script>
@endpush