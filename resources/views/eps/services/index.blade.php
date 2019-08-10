@extends('layouts.backend.template')

@push('styles')
    <link rel="stylesheet" href="{{asset('css/dataTables.bootstrap.min.css')}}">
@endpush

@section('content')
    <div class="title-block">
        <div class="float-left">
            <h3 class="title"> Servicios para EPS: {!! $eps->alias ? $eps->alias : $eps->name !!} registrados en el sistema</h3>
            <p class="title-description"> Aquí puedes ver el listado de todas los servicios para una EPS en específico y crear, actualizar o eliminarlos</p>
        </div>
        <div class="float-right animated fadeInRight">
            <a href="{{ route('eps.services.create', $eps->id) }}" class="btn btn-pill-left btn-primary btn-lg">
                <i class="fa fa-plus"></i>
                Añadir Servicio
            </a>
        </div>
        <br>
        <div class="float-right animated fadeInRight">
            <a href="{{ route('eps.index') }}" class="btn btn-pill-left btn-secondary btn-lg">
                <i class="fas fa-list"></i>
                Regresar
            </a>
        </div>
    </div>

    <section class="section">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-block">
                        <div class="card-title-block">
                            <h3 class="title"> Servicios para: {!! $eps->alias ? $eps->alias : $eps->name !!}</h3>
                        </div>
                        <div class="col-xs-12">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-condensed table-hover" id="myTable">
                                    <thead>
                                    <th>Código</th>
                                    <th>Nombre</th>
                                    <th style="width: 150px;">Monto</th>
                                    <th style="width: 150px;">Opciones</th>
                                    </thead>
                                    <tbody>
                                    @foreach($services as $service)
                                        <tr>
                                            <td>{!! $service->code !!}</td>
                                            <td>{!! $service->name !!}</td>
                                            <td>$ {!! number_format($service->price, 2, ',', '.') !!}</td>
                                            <td>
                                                <a href="{{ route('eps.services.edit', $service->id) }}" class="btn btn-pill-left btn-info btn-sm">
                                                    Editar
                                                </a>
                                                <a href="javascript:showModal('eps-services/delete/{{ $service->id }}')" class="btn btn-pill-right btn-danger btn-sm">
                                                    Borrar
                                                </a>                                                    
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
    </section>
@endsection

@push('scripts')
    <script src="{{asset('js/jquery.dataTables.min.js')}}"></script>
    <script src="{{asset('js/dataTables.bootstrap.min.js')}}"></script>
    <script src="{{asset('js/general/table.js').'?version='.config('constants.stylesVersion')}}"></script>
@endpush
