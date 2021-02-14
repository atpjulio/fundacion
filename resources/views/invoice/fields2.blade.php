<div class="form-group @if ($errors->has('authorization_code')) has-error @endif">
  <label for="authorization_code" class="control-label">Autorización</label>
  {!! Form::text('authorization_code', old('authorization_code', isset($invoice) ? $invoice->authorization_code : ''),
  ['class' => 'form-control underlined', 'readonly', 'id' => 'authorization_code']) !!}
</div>
<div class="form-group @if ($errors->has('total_days')) has-error @endif">
  <label for="total_days" class="control-label">Total de días</label>
  {!! Form::number('total_days', old('total_days', isset($invoice) ? $invoice->days : ''), [
  'class' => 'form-control
  underlined',
  'placeholder' => 'Total de días',
  'min' => '0',
  'id' => 'total_days',
  ]) !!}
</div>
<div class="form-group @if ($errors->has('total')) has-error @endif">
  <label for="total" class="control-label">Monto</label>
  {!! Form::number('total', old('total', isset($invoice) ? $invoice->total : ''), ['class' => 'form-control underlined',
  'placeholder' => 'Valor total de la factura', 'min' => '0', 'id' => 'total']) !!}
</div>
