<div class="modal-header">
  <h4 class="modal-title"><i class="fa fa-warning"></i> Borrar RIPS</h4>
  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
    <span aria-hidden="true">&times;</span>
  </button>
</div>
<form action="{{ route('rip.destroy', ['id' => $rip->id]) }}" method="DELETE">
  @csrf
  <div class="modal-body">
    <p>Confirma que deseas borrar el RIPS: </p>
    <div class="text-center">
      {!! substr($rip->url, 12, 10) !!}
    </div>
  </div>

  <input type="hidden" name="id" value="{{ $rip->id }}">

  <div class="modal-footer">
    <button type="submit" class="btn btn-primary">Si</button>
    <button type="button" class="btn btn-secondary" data-dismiss="modal">No</button>
  </div>
</form>
