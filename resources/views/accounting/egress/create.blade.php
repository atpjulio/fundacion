@extends('layouts.backend.template')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/dataTables.bootstrap.min.css') }}">
@endpush

@section('content')
    <div class="title-block">
        <div class="float-left">
            <h3 class="title"> Nuevo Comprobante de Egreso </h3>
            <p class="title-description"> Añadiendo nuevo comprobante de egreso al sistema </p>
        </div>
        <div class="float-right animated fadeInRight">
            <a href="{{ route('egress.index') }}" class="btn btn-pill-left btn-secondary btn-lg">
                <i class="fas fa-list"></i>
                Regresar
            </a>
        </div>
    </div>
    {!! Form::open(['route' => 'egress.store', 'method' => 'POST']) !!}
    <section class="section">
        <div class="row">
            @include('accounting.egress.fields')
            <div class="col-12 text-center">
                {!! Form::submit('Guardar el comprobante', ['class' => 'btn btn-oval btn-primary']) !!}
            </div>
        </div>
    </section>
    {!! Form::close() !!}
@endsection

@push('scripts')
    <script src="{{ asset('js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('js/dataTables.bootstrap.min.js') }}"></script>
    <script src="{{ asset('js/accounting/receipt/index.js') }}"></script>
@endpush
