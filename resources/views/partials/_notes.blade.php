<div class="table-responsive">
    <table class="table table-striped table-bordered table-condensed table-hover" id="myTable">
        <thead>
        <th style="width: 180px;"># Nota</th>
        <th>Monto</th>
        <th>Fecha</th>
        <th>Opciones</th>
        </thead>
        <tbody>
        @foreach($notes as $note)
            <tr>
                <td>{!! $note->number !!}</td>
                <td>$ {!! number_format($note->amount, 2, ",", ".") !!}</td>
                <td>{!! \Carbon\Carbon::parse($note->created_at)->format("d/m/Y") !!}</td>
                <td>
                    <a href="{{ route('accounting-note.edit', $note->id) }}" class="btn btn-pill-left btn-info btn-sm">
                        Editar
                    </a>
                    <a href="javascript:showModal('accounting-note-delete/{{ $note->id }}')" class="btn btn-pill-right btn-danger btn-sm">
                        Borrar
                    </a>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
@if (count($notes) > 0)
  <div class="float-right">
      {{ $notes->links() }}
  </div>
@endif
