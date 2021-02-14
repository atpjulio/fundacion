<div class="modal-header">
  <h4 class="modal-title"><i class="fa fa-warning"></i> Borrar Servicio</h4>
  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
    <span aria-hidden="true">&times;</span>
  </button>
</div>
<form action="{{ route('eps.services.destroy', ['id' => $service->id]) }}" method="DELETE">
  @csrf
  <div class="modal-body">
    <p>Confirma que deseas borrar el servicio: </p>
    <div class="text-center">
      {!! $service->code . ' -> ' . $service->name !!}
    </div>
  </div>

  <input type="hidden" name="id" value="{{ $service->id }}">
  <input type="hidden" name="eps_id" value="{{ $service->eps_id }}">

  <div class="modal-footer">
    <button type="submit" class="btn btn-primary">Si</button>
    <button type="button" class="btn btn-secondary" data-dismiss="modal">No</button>
  </div>
</form>
