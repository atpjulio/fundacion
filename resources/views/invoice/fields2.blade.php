<div class="form-group @if($errors->has('authorization_code')) has-error @endif">
    {!! Form::label('authorization_code', 'Autorización', ['class' => 'control-label']) !!}
    {!! Form::text('authorization_code', old('authorization_code', isset($invoice) ? $invoice->authorization_code : ''), ['class' => 'form-control underlined', 'readonly', 'id' => 'authorization_code']) !!}
</div>
<div class="form-group @if($errors->has('total_days')) has-error @endif">
    {!! Form::label('total_days', 'Total de días', ['class' => 'control-label']) !!}
    {!! Form::number('total_days', old('total_days', isset($invoice) ? \Carbon\Carbon::parse($invoice->authorization->date_to)->diffInDays(\Carbon\Carbon::parse($invoice->authorization->date_from)) : ''), ['class' => 'form-control underlined', 'placeholder' => 'Total de días', 'min' => '0', 'id' => 'total_days']) !!}
</div>
<div class="form-group @if($errors->has('total')) has-error @endif">
    {!! Form::label('total', 'Monto', ['class' => 'control-label']) !!}
    {!! Form::number('total', old('total', isset($invoice) ? $invoice->total : ''), ['class' => 'form-control underlined', 'placeholder' => 'Valor total de la factura', 'min' => '0', 'readonly', 'id' => 'total']) !!}
</div>
