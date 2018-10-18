<div class="modal fade" id="confirm-modal-{{ $rip->id }}">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title"><i class="fa fa-warning"></i> Borrar RIPS</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            {!! Form::open(['route' => ['rip.destroy', $rip->id], 'method' => 'DELETE']) !!}
            <div class="modal-body">
                <p>Confirma que deseas borrar el RIPS para : </p>
                <div class="text-center">
                    {!! \Carbon\Carbon::parse($rip->created_at)->format("d/m/Y") !!}
                </div>
            </div>

            {!! Form::hidden('id', $rip->id) !!}

            <div class="modal-footer">
                <button type="submit" class="btn btn-primary">Si</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">No</button>
            </div>
            {!! Form::close() !!}
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
