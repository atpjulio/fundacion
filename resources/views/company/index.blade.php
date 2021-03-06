@extends('layouts.backend.template')

@push('styles')
    <link rel="stylesheet" href="{{asset('css/dataTables.bootstrap.min.css')}}">
@endpush

@section('content')
    <div class="title-block">
        <div class="float-left">
            <h3 class="title"> Administrar Compañías </h3>
            <p class="title-description"> Aquí puedes ver el listado de todos las compañías y crear, actualizar o eliminar cualquiera de ellas </p>
        </div>
        <div class="float-right animated fadeInRight">
            <a href="{{ route('company.create') }}" class="btn btn-pill-left btn-primary btn-lg">
                <i class="fa fa-plus"></i>
                Nueva Compañía
            </a>
        </div>
    </div>

    <section class="section">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-block">
                        <div class="card-title-block">
                            <h3 class="title"> Compañías registradas en el sistema </h3>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-condensed table-hover" id="myTable">
                                <thead>
                                <th>Nombre</th>
                                <th>Documento</th>
                                <th>Resolución de Fact.</th>
                                <th>Opciones</th>
                                </thead>
                                <tbody>
                                @foreach($companies as $company)
                                    <tr>
                                        <td>{!! $company->name !!}</td>
                                        <td>{!! $company->doc !!}</td>
                                        <td>{!! $company->billing_resolution !!}</td>
                                        <td>
                                            @if ($company->id == 1)
                                                <a href="{{ route('company.edit', $company->id) }}" class="btn btn-oval btn-info btn-sm">
                                                    Editar
                                                </a>
                                            @else
                                                <a href="{{ route('company.edit', $company->id) }}" class="btn btn-pill-left btn-info btn-sm">
                                                    Editar
                                                </a>
                                                <a href="" data-toggle="modal" data-target="#confirm-modal-{{ $company->id }}" class="btn btn-pill-right btn-danger btn-sm">
                                                    Borrar
                                                </a>
                                            @endif
                                        </td>
                                        @include('company.delete_modal')
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
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
    <script src="{{ asset('js/general/table.js') }}"></script>
@endpush