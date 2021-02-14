<div class="form-group @if ($errors->has('authorization_code')) has-error @endif">
  <label for="authorization_code" class="control-label">Autorización</label>
  <input type="text" name="authorization_code" id="authorization_code" readonly class="form-control underlined"
    value="{{ old('authorization_code', isset($invoice) ? $invoice->authorization_code : '') }}">
</div>
<div class="form-group @if ($errors->has('total_days')) has-error @endif">
  <label for="total_days" class="control-label">Total de días</label>
  <input type="number" name="total_days" id="total_days" class="form-control underlined" min="0"
    value="{{ old('total_days', isset($invoice) ? $invoice->days : '') }}">
</div>
<div class="form-group @if ($errors->has('total')) has-error @endif">
  <label for="total" class="control-label">Monto</label>
  <input type="number" name="total" id="total" class="form-control underlined" min="0"
    value="{{ old('total', isset($invoice) ? $invoice->total : '') }}">
</div>
