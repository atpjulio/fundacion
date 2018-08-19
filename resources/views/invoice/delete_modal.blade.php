<div class="modal fade" id="confirm-modal-{{ $invoice->id }}">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title"><i class="fa fa-warning"></i> Borrar Factura</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            {!! Form::open(['route' => ['invoice.destroy', $invoice->id], 'method' => 'DELETE']) !!}
            <div class="modal-body">
                <p>Confirma que deseas borrar la factura: </p>
                <div class="text-center">
                    {!! $invoice->number !!}
                </div>
            </div>

            {!! Form::hidden('id', $invoice->id) !!}

            <div class="modal-footer">
                <button type="submit" class="btn btn-primary">Si</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">No</button>
            </div>
            {!! Form::close() !!}
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
