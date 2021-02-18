<div class="form-group  @if ($errors->has('created_at')) has-error @endif">
  <label for="created_at" class="control-label">Fecha de generación</label>
  <input type="date" name="created_at" placeholder="dd/mm/aaaa" class="form-control underlined" value="{{ old('created_at', isset($rip) ? $rip->created_at : now()) }}" @isset($show) readonly @endisset>
</div>

<div id="dynamic-invoice-amount">
  <div class="form-group">
    <label for="hint" class="control-label">Facturas disponibles para EPS</label>
    @if ($invoicesAmount > 0)
      <div class="alert alert-dark" role="alert">
        Total: {!! $invoicesAmount !!} factura(s) disponible(s) para el rango de facturas seleccionado
      </div>
    @else
      <div class="alert alert-warning" role="alert" style="background-color: #fff3cd; color: #856404;">
        No hay facturas disponibles para el rango de facturas seleccionado
      </div>
    @endif
  </div>
</div>
