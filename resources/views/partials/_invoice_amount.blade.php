<div class="form-group">
  <label for="hint" class="control-label">Facturas disponibles para EPS</label>
  @if ($invoicesAmount > 0)
    <div class="alert alert-dark" role="alert">
      Total: {!! $invoicesAmount !!} factura(s) disponible(s) para el rango seleccionado
    </div>
  @else
    <div class="alert alert-warning" role="alert" style="background-color: #fff3cd; color: #856404;">
      No hay facturas disponibles para el rango seleccionado
    </div>
  @endif
</div>
