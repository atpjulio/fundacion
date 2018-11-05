@extends('layouts.backend.template')

@push('styles')
    <link rel="stylesheet" href="{{asset('css/dataTables.bootstrap.min.css')}}">
@endpush

@section('content')
    <div class="title-block">
        <div class="float-left">
            <h3 class="title"> Nueva Factura </h3>
            <p class="title-description"> Añadiendo nueva factura al sistema </p>
        </div>
        <div class="float-right animated fadeInRight">
            <a href="{{ route('invoice.index') }}" class="btn btn-pill-left btn-secondary btn-lg">
                <i class="fas fa-list"></i>
                Listado
            </a>
        </div>
    </div>
    {!! Form::open(['route' => 'invoice.store', 'method' => 'POST']) !!}
    <section class="section">
        <div class="row">
            @include('invoice.fields')
            <div class="col-12">
                <div class="text-center">
                    {!! Form::submit('Guardar la factura', ['class' => 'btn btn-oval btn-primary']) !!}
                </div>
            </div>            
        </div>
    </section>
    {!! Form::hidden('selected_price', 0, ['id' => 'selected_price']) !!}
    {!! Form::close() !!}
@endsection

@push('scripts')
    <script src="{{asset('js/jquery.dataTables.min.js')}}"></script>
    <script src="{{asset('js/dataTables.bootstrap.min.js')}}"></script>
    <script src="{{asset('js/invoice/create.js').'?version='.config('constants.stylesVersion')}}"></script>
@endpush
