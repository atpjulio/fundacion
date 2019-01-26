<div class="table-responsive">
    <table class="table table-striped table-bordered table-condensed table-hover" id="myTable">
        <thead>
        <th style="width: 160px;"># Comprobante</th>
        <th>Pagado a</th>
        <th>NIT/CC</th>
        <th style="width: 110px;">Monto</th>
        <th style="width: 300px;">Opciones</th>
        </thead>
        <tbody>
        @foreach($egresses as $egress)
            <tr>
                <td>{!! $egress->number !!}</td>
                <td>{!! $egress->entity->name !!}</td> 
                <td>{!! $egress->entity->doc !!}</td>
                <td>$ {!! number_format($egress->amount, 2, ",", ".") !!}</td>
                <td>
                    <a href="{{ route('egress.edit', $egress->id) }}" class="btn btn-pill-left btn-info btn-sm">
                        Editar
                    </a>
                    <a href="{{ route('egress.pdf', $egress->id) }}" class="btn btn-secondary btn-sm" target="_blank">
                        Ver comprobante
                    </a>
                    <a href="javascript:showModal('egress-delete/{{ $egress->id }}')" class="btn btn-pill-right btn-danger btn-sm">
                        Borrar
                    </a>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>            
@if (count($egresses) > 0)
  <div class="float-right">
      {{ $egresses->links() }}
  </div>
@endif
