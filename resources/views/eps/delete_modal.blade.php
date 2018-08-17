<div class="modal fade" id="confirm-modal-{{ $eps->id }}">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title"><i class="fa fa-warning"></i> Borrar EPS</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            {!! Form::open(['route' => ['eps.destroy', $eps->id], 'method' => 'DELETE']) !!}
            <div class="modal-body">
                <p>Confirma que deseas borrar la EPS: </p>
                <div class="text-center">
                    {!! $eps->code.' -> '.$eps->name !!}
                </div>
            </div>

            {!! Form::hidden('id', $eps->id) !!}

            <div class="modal-footer">
                <button type="submit" class="btn btn-primary">Si</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">No</button>
            </div>
            {!! Form::close() !!}
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
