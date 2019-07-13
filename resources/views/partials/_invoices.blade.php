<div class="table-responsive">
    <table class="table table-striped table-bordered table-condensed table-hover" id="">
        <thead>
        <th># Factura</th>
        <th>Autorización</th>
        <th>Monto</th>
        <th>Cant.</th>
        <th style="width: 250px;">Opciones</th>
        </thead>
        <tbody>
            @foreach($invoices as $invoice)
            <tr>
                <td>{!! $invoice->format_number !!}</td>
                <td>{!! !$invoice->multiple ? $invoice->authorization_code : join("<br>", json_decode($invoice->multiple_codes, true)) !!}</td>
                <td>$ {!! !$invoice->multiple ? number_format($invoice->total, 2, ",", ".") : join("<br>$ ", $invoice->multiple_totals_formated)!!}</td>
                <td>{!! !$invoice->multiple ? $invoice->days : join("<br>", json_decode($invoice->multiple_days, true)) !!}</td>
                <td>
                    <a href="{{ route('invoice.edit', $invoice->id) }}" class="btn btn-pill-left btn-info btn-sm">
                        Editar
                    </a>
                    <a href="{{ route('invoice.pdf', $invoice->id) }}" class="btn btn-secondary btn-sm" target="_blank">
                        Ver factura
                    </a>
                    <a href="javascript:showModal('invoice/delete/{{ $invoice->id }}')" class="btn btn-pill-right btn-danger btn-sm">
                        Borrar
                    </a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@if (count($invoices) > 0)
  <div class="float-right">
      {{ $invoices->links() }}
  </div>
@endif
