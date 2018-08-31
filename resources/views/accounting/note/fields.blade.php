<div class="form-group @if($errors->has('invoice_number')) has-error @endif">
    {!! Form::label('invoice_number', 'Número de factura seleccionada', ['class' => 'control-label']) !!}
    {!! Form::text('invoice_number', old('invoice_number', isset($note) ? $note->invoice->number : ''), ['class' => 'form-control underlined', 'readonly', 'id' => 'invoice_number']) !!}
</div>
<div class="form-group @if($errors->has('amount')) has-error @endif">
    {!! Form::label('amount', 'Monto de la nota', ['class' => 'control-label']) !!}
    {!! Form::number('amount', old('amount', isset($note) ? $note->amount : 0), ['class' => 'form-control underlined', 'placeholder' => 'Monto de la nota', 'min' => '0']) !!}
</div>
<div class="form-group  @if($errors->has('created_at')) has-error @endif">
    {!! Form::label('created_at', 'Fecha de la nota', ['class' => 'control-label']) !!}
    {!! Form::date('created_at', old('created_at', isset($note) ? $note->created_at : \Carbon\Carbon::now()), ['class' => 'form-control underlined', 'placeholder' => 'dd/mm/aaaa', isset($show) ? 'readonly' : '']) !!}
</div>
