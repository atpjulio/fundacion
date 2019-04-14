<div class="table-responsive">
    <table class="table table-striped table-bordered table-condensed table-hover" id="myTable">
        <thead>
        <th style="width: 150px;"># Recibo</th>
        <th>Recibido de</th>
        <th style="width: 160px;">Monto del recibo</th>
        <th>Fecha</th>
        <th style="width: 240px;">Opciones</th>
        </thead>
        <tbody>
        @foreach($receipts as $receipt)
            <tr>
                <td>{!! $receipt->number !!}</td>
                <td>{!! $receipt->entity->name !!}</td>
                <td>$ {!! number_format($receipt->amount, 2, ",", ".") !!}</td>
                <td>{!! \Carbon\Carbon::parse($receipt->created_at)->format("d/m/Y") !!}</td>
                <td>
                    <a href="{{ route('receipt.edit', $receipt->id) }}" class="btn btn-pill-left btn-info btn-sm">
                        Editar
                    </a>
                    <a href="{{ route('receipt.pdf', $receipt->id) }}" class="btn btn-secondary btn-sm" target="_blank">
                        Ver recibo
                    </a>
                    <a href="javascript:showModal('receipt/delete/{{ $receipt->id }}')" class="btn btn-pill-right btn-danger btn-sm">
                        Borrar
                    </a>                                                
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
@if (count($receipts) > 0)
  <div class="float-right">
      {{ $receipts->links() }}
  </div>
@endif
