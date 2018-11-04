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
            <div class="col-12">
                <div class="card">
                    <div class="card-block">
                        <div class="card-title-block">
                            <h3 class="title"> Autorización para esta factura</h3>
                        </div>
                        <div class="col-12">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-condensed table-hover" id="myTable">
                                    <thead>
                                    <th>Código</th>
                                    <th>EPS</th>
                                    <th>Usuario</th>
                                    <th>Desde</th>
                                    <th>Hasta</th>
                                    <th>Total Días</th>
                                    <th>Opciones</th>
                                    </thead>
                                    <tbody>
                                    @foreach($authorizations as $key => $authorization)
                                        <tr>
                                            <td>{!! $authorization->codec !!}</td>
                                            <td>{!! $authorization->eps->alias ? $authorization->eps->alias : $authorization->eps->name !!}</td>
                                            <td>{!! $authorization->patient->full_name !!}</td>
                                            <td>{!! \Carbon\Carbon::parse($authorization->date_from)->format("d/m/Y") !!}</td>
                                            <td>{!! \Carbon\Carbon::parse($authorization->date_to)->format("d/m/Y") !!}</td>
                                            <td>{!! $authorization->days !!}</td>
                                            <td>
                                                {!! Form::button('Seleccionar', ['class' => 'btn btn-oval btn-success', 'id' => 'button'.$key ]) !!}
                                                {!! Form::hidden('daily_price', $authorization->eps->daily_price, ['id' => 'daily_price']) !!}
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
            <div id="beginning" class="col-md-6">
                <div class="card">
                    <div class="card-block">
                        @include('invoice.fields')
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-block">
                        @include('invoice.fields2')
                    </div>
                </div>
            </div>
            <div class="col-12">
                <div class="text-right">
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
    <script src="{{asset('js/invoice/index.js').'?version='.config('constants.stylesVersion')}}"></script>
@endpush
