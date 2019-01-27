@extends('layouts.backend.template')

@push('styles')
    <link rel="stylesheet" href="{{asset('css/dataTables.bootstrap.min.css')}}">
@endpush

@section('content')
    <div class="title-block">
        <div class="float-left">
            <h3 class="title"> Recibos de pago </h3>
            <p class="title-description"> Aquí puedes ver el listado de todos los recibos de pago y crear, actualizar o eliminar cualquiera de ellos </p>
        </div>
        <div class="float-right animated fadeInRight">
            <a href="{{ route('receipt.create') }}" class="btn btn-pill-left btn-primary btn-lg">
                <i class="fa fa-plus"></i>
                Nuevo Recibo
            </a>            
        </div>
        <div class="float-right animated fadeInRight">
            <a href="{{ route('receipt.import') }}" class="btn btn-pill-left btn-secondary btn-lg">
                <i class="fas fa-file-import"></i>
                Importar Recibos
            </a>
        </div> 
    </div>

    <section class="section">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-block">
                        <div class="card-title-block">
                            <div class="float-left">
                                <h3 class="title"> Recibos registrados en el sistema </h3>
                                </div>
                            <div class="dataTables_filter float-right form-inline mb-3 mt-0">
                                <label class="mr-2">Buscar:</label>
                                <input type="search" class="form-control form-control-sm" placeholder="" id="searching">
                            </div>      
                        </div>
                        <div id="dynamic-receipts">
                            @include('partials._receipts')
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
    <script src="{{ asset('js/accounting/receipt/filter.js').'?version='.config('constants.stylesVersion') }}"></script>
@endpush
