@extends('layouts.backend.template')

@push('styles')
    <link rel="stylesheet" href="{{asset('css/dataTables.bootstrap.min.css')}}">
@endpush

@section('content')
    <div class="title-block">
        <div class="float-left">
            <h3 class="title"> Listado de Autorizaciones Cerradas (Total: {{ number_format($total, 0, ",", ".") }})</h3>
            <p class="title-description"> Aquí puedes ver el listado de las autorizaciones cerradas </p>
        </div>
        <div class="float-right animated fadeInRight">
            <a href="{{ route('authorization.create') }}" class="btn btn-pill-left btn-primary btn-lg">
                <i class="fa fa-plus"></i>
                Nueva Autorización
            </a>
        </div>
    </div>

    <section class="section">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-block">
                        <div class="card-title-block">
                            <h3 class="title"> Autorizaciones cerradas en el sistema </h3>
                        <div id="dynamic-authorizations">
                            @include('partials._authorizations')
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
    <script src="{{ asset('js/authorization/filter.js') }}"></script>
@endpush