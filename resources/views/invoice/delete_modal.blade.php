<div class="modal-header">
  <h4 class="modal-title"><i class="fa fa-warning"></i> Borrar Factura</h4>
  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
    <span aria-hidden="true">&times;</span>
  </button>
</div>
<form method="POST" action="{{ route('invoice.destroy', ['id' => $invoice->id]) }}">
  @csrf
  <input type="hidden" name="_method" value="DELETE">

  <div class="modal-body">
    <p>Confirma que deseas borrar la factura: </p>
    <div class="text-center">
      {!! $invoice->format_number !!}
    </div>
  </div>

  <input type="hidden" name="id" value="{{ $invoice->id }}">

  <div class="modal-footer">
    <button type="submit" class="btn btn-primary">Si</button>
    <button type="button" class="btn btn-secondary" data-dismiss="modal">No</button>
  </div>
</form>
