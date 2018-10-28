<div class="form-group @if($errors->has('invoice_number')) has-error @endif">
    {!! Form::label('invoice_number', 'Número de factura seleccionada', ['class' => 'control-label']) !!}
    {!! Form::text('invoice_number', old('invoice_number', isset($receipt) ? $receipt->invoice->number : ''), ['class' => 'form-control underlined', 'readonly', 'id' => 'invoice_number']) !!}
</div>
<div class="form-group  @if($errors->has('created_at')) has-error @endif">
    {!! Form::label('created_at', 'Fecha del recibo de pago', ['class' => 'control-label']) !!}
    {!! Form::date('created_at', old('created_at', isset($receipt) ? $receipt->created_at : \Carbon\Carbon::now()), ['class' => 'form-control underlined', 'placeholder' => 'dd/mm/aaaa', isset($show) ? 'readonly' : '']) !!}
</div>
