<div class="modal fade" id="confirm-modal-{{ $authorization->id }}">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title"><i class="fa fa-warning"></i> Borrar Autorización</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            {!! Form::open(['route' => ['authorization.destroy', $authorization->id], 'method' => 'DELETE']) !!}
            <div class="modal-body">
                <p>Confirma que deseas borrar la autorización: </p>
                <div class="text-center">
                    {!! $authorization->codec !!}
                </div>
                {{ $authorization->id }}
                <input type="number" id="test_number" name="test_number" class="form-control" value="1" min=0>
                <input type="number" id="test_result" name="test_result" class="form-control" value="1">
            </div>

            {!! Form::hidden('id', $authorization->id) !!}

            <div class="modal-footer">
                <button type="submit" class="btn btn-primary">Si</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">No</button>
            </div>
            {!! Form::close() !!}
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
