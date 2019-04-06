@extends('layouts.backend.template')

@section('content')
    <div class="title-block">
        <div class="float-left">
            <h3 class="title"> Comprobante de Egresos por Mes</h3>
            <p class="title-description"> Generar balance de comprobante de egresos para un mes </p>
        </div>
        <div class="float-right animated fadeInRight">
            <a href="{{ route('egress.index') }}" class="btn btn-pill-left btn-secondary btn-lg">
                <i class="fas fa-list"></i>
                Listado
            </a>
        </div>
    </div>
    {!! Form::open(['route' => 'egress.balance.pdf', 'method' => 'GET', 'target' => '_blank']) !!}
    <section class="section">
        <div class="row">
            @include('accounting.egress.volume_fields')
            <div class="col-6">
                <div class="text-center">
                    {!! Form::submit('Generar Balance del Mes', ['class' => 'btn btn-oval btn-primary']) !!}
                </div>
            </div>
        </div>
    </section>
    {!! Form::close() !!}
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            $('#month').on('change', function() {
                var data = $('#year').val() + '-' + $('#month').val();
                fillEgressesByDate(data);
            });

            $('#year').on('change', function() {
                var data = $('#year').val() + '-' + $('#month').val();
                fillEgressesByDate(data);
            });
        } );
    </script>
@endpush
