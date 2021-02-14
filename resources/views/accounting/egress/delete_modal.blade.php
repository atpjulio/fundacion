<div class="modal-header bg-danger">
  <h4 class="modal-title"><i class="fa fa-warning"></i> Borrar Comprobante de Egreso</h4>
  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
    <span aria-hidden="true">&times;</span>
  </button>
</div>
<form action="{{ route('egress.destroy', $egress->id) }}" method="DELETE">
  @csrf

  <div class="modal-body">
    <p>Confirma que deseas borrar el comprobante de egreso: </p>
    <div class="text-center">
      Número {!! sprintf('%05d', $egress->id) !!}
    </div>
  </div>

  <div class="modal-footer">
    <button type="submit" class="btn btn-primary">Si</button>
    <button type="button" class="btn btn-secondary" data-dismiss="modal">No</button>
  </div>
</form>
