<div class="modal-header">
  <h4 class="modal-title"><i class="fa fa-warning"></i> Borrar Recibo de Pago</h4>
  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
    <span aria-hidden="true">&times;</span>
  </button>
</div>
<form action="{{ route('receipt.destroy', $receipt->id) }}" method="DELETE">
  @csrf
  <div class="modal-body">
    <p>Confirma que deseas borrar el recibo: </p>
    <div class="text-center">
      Número {!! $receipt->number !!}
    </div>
  </div>
  <div class="modal-footer">
    <button type="submit" class="btn btn-primary">Si</button>
    <button type="button" class="btn btn-secondary" data-dismiss="modal">No</button>
  </div>
</form>
