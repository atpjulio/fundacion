@extends('layouts.backend.template')

@push('styles')
    <link rel="stylesheet" href="{{asset('css/dataTables.bootstrap.min.css')}}">
@endpush

@section('content')
    <div class="title-block">
        <div class="float-left">
            <h3 class="title"> Contabilidad por EPS </h3>
            <p class="title-description"> Diversas opciones de manejo de contabilidad por EPS </p>
        </div>
        {{--
        <div class="float-right animated fadeInRight">
            <a href="{{ URL::previous() }}" class="btn btn-pill-left btn-secondary btn-lg">
                <i class="fas fa-list"></i>
                Regresar
            </a>
        </div>
        --}}
    </div>
    <section class="section">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-block">
                        <div class="card-title-block">
                            <h3 class="title"> Facturas por EPS </h3>
                        </div>
                        <div class="col-12">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-condensed table-hover" id="myTable">
                                    <thead>
                                    <th>EPS</th>
                                    <th>Pendientes</th>
                                    <th>Pagadas</th>
                                    <th>Total Pendiente</th>
                                    <!--<th>Fecha de creación</th>-->
                                    <th>Opciones</th>
                                    </thead>
                                    <tbody>
                                    @if (count($rows['eps_id']) > 0)
                                    @foreach($rows['eps_id'] as $key => $row)
                                        <tr>
                                            <td>{!! $rows['eps_name'][$key] !!}</td>
                                            <td>{!! $rows['pending'][$key] !!}</td>
                                            <td>{!! $rows['paid'][$key] !!}</td>
                                            <td>$ {!! number_format($rows['pending_amount'][$key], 2, ",", ".") !!}</td>
                                            {{--<td>{!! \Carbon\Carbon::parse($authorization->created_at)->format("d/m/Y") !!}</td>--}}
                                            <td>
                                                @role('admin')
	                                                @if($rows['pending_amount'][$key] <= 0)
	                                                	Sin facturas
	                                                @else
		                                                <a href="{{ route('accounting.eps.detail', $row) }}" class="btn btn-oval btn-info btn-sm">
		                                                    Detalles
		                                                </a>
		                                                
	                                                @endif
                                                @endrole
                                            </td>
                                        </tr>
                                        {{--@include('authorization.delete_modal')--}}
                                    @endforeach
                                    @endif
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
        } );
    </script>
@endpush