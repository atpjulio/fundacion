<div class="modal-header">
    <h4 class="modal-title"><i class="fa fa-warning"></i> Borrar Servicio</h4>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
{!! Form::open(['route' => ['eps.services.destroy', $service->id], 'method' => 'DELETE']) !!}
    <div class="modal-body">
        <p>Confirma que deseas borrar el servicio: </p>
        <div class="text-center">
            {!! $service->code.' -> '.$service->name !!}
        </div>
    </div>

    {!! Form::hidden('id', $service->id) !!}
    {!! Form::hidden('eps_id', $service->eps_id) !!}

    <div class="modal-footer">
        <button type="submit" class="btn btn-primary">Si</button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">No</button>
    </div>
{!! Form::close() !!}
