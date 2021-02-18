<div class="row">
  <div class="col-md-6">
    <div class="form-group  @if ($errors->has('initial_date')) has-error @endif">
      <label for="initial_date" class="control-label">Fecha de remisión desde</label>
      <input type="date" name="initial_date" placeholder="dd/mm/aaaa" class="form-control underlined"
        value="{{ old('initial_date', isset($rip) ? $rip->initial_date : now()) }}" @isset($show) readonly @endisset>
    </div>
  </div>
  <div class="col-md-6">
    <div class="form-group  @if ($errors->has('final_date')) has-error @endif">
      <label for="final_date" class="control-label">Fecha de remisión hasta</label>
      <input type="date" name="final_date" placeholder="dd/mm/aaaa" class="form-control underlined"
        value="{{ old('final_date', isset($rip) ? $rip->final_date : now()) }}" @isset($show) readonly @endisset>
    </div>
  </div>
</div>

<div id="dynamic-invoice-amount">
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
</div>
