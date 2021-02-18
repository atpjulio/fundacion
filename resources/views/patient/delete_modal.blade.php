<div class="modal-header">
  <h4 class="modal-title"><i class="fa fa-warning"></i> Borrar Usuario</h4>
  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
    <span aria-hidden="true">&times;</span>
  </button>
</div>
<form method="POST" action="{{ route('patient.destroy', ['id' => $patient->id]) }}">
  @csrf
  <input type="hidden" name="_method" value="DELETE">
  <div class="modal-body">
    <p>Confirma que deseas borrar al usuario: </p>
    <div class="text-center">
      {!! $patient->full_name !!}
    </div>
  </div>

  <input type="hidden" name="id" value="{{ $patient->id }}">

  <div class="modal-footer">
    <button type="submit" class="btn btn-primary">Si</button>
    <button type="button" class="btn btn-secondary" data-dismiss="modal">No</button>
  </div>
</form>
