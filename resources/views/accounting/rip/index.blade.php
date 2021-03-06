@extends('layouts.backend.template')

@push('styles')
    <link rel="stylesheet" href="{{asset('css/dataTables.bootstrap.min.css')}}">
@endpush

@section('content')
    <div class="title-block">
        <div class="float-left">
            <h3 class="title"> Registros Individuales de Prestación de Servicios de Salud </h3>
            <p class="title-description"> Aquí puedes ver el listado de todas los RIPS generados en el sistema </p>
        </div>
        <div class="float-right animated fadeInRight">
            <a href="{{ route('rip.create') }}" class="btn btn-pill-left btn-primary btn-lg">
                <i class="fa fa-plus"></i>
                Nuevo RIPS
            </a>
        </div>
    </div>

    <section class="section">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-block">
                        <div class="card-title-block">
                            <h3 class="title"> RIPS registrados en el sistema </h3>
                        </div>
                        <div class="col-12">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-condensed table-hover" id="myTable">
                                    <thead>
                                    <th>EPS</th>
                                    <th>Fecha inicial</th>
                                    <th>Fecha final</th>
                                    <th>Fecha de remisión</th>
                                    <th>Archivo</th>
                                    <th>Opciones</th>
                                    </thead>
                                    <tbody>
                                    @foreach($rips as $rip)
                                        <tr>
                                            <td>{!! $rip->eps->alias ?: $rip->eps->name !!}</td>
                                            <td>{!! \Carbon\Carbon::parse($rip->initial_date)->format("d/m/Y") !!}</td>
                                            <td>{!! \Carbon\Carbon::parse($rip->final_date)->format("d/m/Y") !!}</td>
                                            <td>{!! \Carbon\Carbon::parse($rip->created_at)->format("d/m/Y") !!}</td>
                                            <td>
                                                <a href="{{ route('rip.download', $rip->id) }}">
                                                    {!! substr($rip->url, 12) !!}
                                                </a>
                                            </td>
                                            <td>
                                                <a href="javascript:showModal('rip/delete/{{ $rip->id }}')" class="btn btn-pill-right btn-danger btn-sm">
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
    <script src="{{ asset('js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('js/dataTables.bootstrap.min.js') }}"></script>
    <script src="{{ asset('js/accounting/rip/index.js') }}"></script>
@endpush