
@if (isset($invoice))
    <div class="form-group @if($errors->has('number')) has-error @endif">
        {!! Form::label('number', 'Número de factura', ['class' => 'control-label']) !!}
        {!! Form::number('number', $invoice->number, ['class' => 'form-control underlined', 'placeholder' => 'Número de factura', 'min' => 1, $lastNumber > 0 ? 'readonly' : '']) !!}
    </div>
@else
    <div class="form-group @if($errors->has('number')) has-error @endif">
        {!! Form::label('number', 'Número de factura', ['class' => 'control-label']) !!}
        {!! Form::number('number', ($lastNumber > 0) ? ($lastNumber + 1) : 1, ['class' => 'form-control underlined', 'placeholder' => 'Número de factura', 'min' => 1, $lastNumber > 0 ? 'readonly' : '']) !!}
    </div>
@endif
<div class="form-group  @if($errors->has('created_at')) has-error @endif">
    {!! Form::label('created_at', 'Fecha de la factura', ['class' => 'control-label']) !!}
    {!! Form::date('created_at', old('created_at', isset($invoice) ? $invoice->created_at : \Carbon\Carbon::now()), ['class' => 'form-control underlined', 'placeholder' => 'dd/mm/aaaa', isset($show) ? 'readonly' : '']) !!}
</div>
<div class="form-group @if($errors->has('company_id')) has-error @endif">
    {!! Form::label('company_id', 'Compañía a la que pertenece la factura', ['class' => 'control-label']) !!}
    {!! Form::select('company_id', $companies, old('company_id', isset($invoice) ? $invoice->company_id : ''), ['class' => 'form-control'   ]) !!}
</div>
