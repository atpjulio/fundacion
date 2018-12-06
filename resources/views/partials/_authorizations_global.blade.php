<div class="table-responsive">
    <div id="" class="dataTables_filter float-right form-inline mb-3 mt-0">
        <label class="mr-2">Buscar:</label>
        <input type="search" class="form-control form-control-sm" placeholder="" id="searching">
    </div>
    <table class="table table-striped table-bordered table-condensed table-hover" id="myTable">
        <thead>
        <th>Código</th>
        <th>EPS</th>
        <th>Estado</th>
        <th>Factura</th>
        <th>Días</th>
        <th>Opciones</th>
        </thead>
        <tbody>
    	@if (count($authorizations) > 0)
        @foreach($authorizations as $authorization)
            <tr>
                <td>{!! $authorization->codec ?: '--' !!}</td>
                <td>{!! $authorization->eps->code !!} - {!! $authorization->eps->alias ? $authorization->eps->alias : $authorization->eps->name !!}</td>
                <td>{!! $authorization->invoice_id > 0 ? 'Cerrada' : 'Abierta' !!}</td>
                <td>{!! $authorization->invoice ? $authorization->invoice->number : '--' !!}</td>
                <td>{!! $authorization->days !!}</td>
                <td>
                    @role('admin')
                    <a href="{{ route('authorization.edit', $authorization->id) }}" class="btn btn-pill-left btn-info btn-sm">
                        Editar
                    </a>
                    <a href="{{ route('authorization.excel', $authorization->id) }}" class="btn btn-secondary btn-sm">
                        Planilla
                    </a>
                    <a href="" data-toggle="modal" data-target="#confirm-modal-{{ $authorization->id }}" class="btn btn-pill-right btn-danger btn-sm">
                        Borrar
                    </a>
                    @endrole
                    @role('user')
                    <a href="{{ route('authorization.edit', $authorization->id) }}" class="btn btn-pill-left btn-info btn-sm">
                        Editar
                    </a>
                    <a href="{{ route('authorization.excel', $authorization->id) }}" class="btn btn-pill-right btn-secondary btn-sm">
                        Planilla
                    </a>
                    @endrole
                </td>
            </tr>
            @include('authorization.delete_modal')
        @endforeach
        @else
            <tr>
                <td colspan="6" align="center">
                    Sin resultados que mostrar
                </td>
            </tr>
        @endif
        </tbody>
    </table>
</div>
@if (count($authorizations) > 0)
<div class="float-right">
    {{ $authorizations->links() }}
</div>
@endif
