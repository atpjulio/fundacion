<div class="modal-header bg-danger">
  <h4 class="modal-title"><i class="fa fa-warning"></i> Borrar Nota Interna</h4>
  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
    <span aria-hidden="true">&times;</span>
  </button>
</div>
<form method="POST" action="{{ route('accounting-note.destroy', ['id' => $note->id]) }}">
  @csrf
  <input type="hidden" name="_method" value="DELETE">
  <div class="modal-body">
    <p>Confirma que deseas borrar la nota: </p>
    <div class="text-center">
      Número {!! sprintf('%05d', $note->id) !!}
    </div>
  </div>

  <input type="hidden" name="id" value="{{ $note->id }}">

  <div class="modal-footer">
    <button type="submit" class="btn btn-primary">Si</button>
    <button type="button" class="btn btn-secondary" data-dismiss="modal">No</button>
  </div>
</form>
