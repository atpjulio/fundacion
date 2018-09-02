   <div class="form-group @if($errors->has('billing_date')) has-error @endif">
    {!! Form::label('billing_date', 'Fecha de resolución de facturación', ['class' => 'control-label']) !!}
    {!! Form::date('billing_date', old('billing_date', isset($company) ? $company->billing_date : ''), ['class' => 'form-control underlined', 'placeholder' => 'dd/mm/aaaa']) !!}
</div>
<div class="form-group @if($errors->has('billing_start')) has-error @endif">
    {!! Form::label('billing_start', 'Desde', ['class' => 'control-label']) !!}
    {!! Form::number('billing_start', old('billing_start', isset($company) ? $company->billing_start : ''), ['class' => 'form-control underlined', 'placeholder' => 'Desde', 'min' => 0]) !!}
</div>
<div class="form-group @if($errors->has('billing_end')) has-error @endif">
    {!! Form::label('billing_end', 'Hasta', ['class' => 'control-label']) !!}
    {!! Form::number('billing_end', old('billing_end', isset($company) ? $company->billing_end : ''), ['class' => 'form-control underlined', 'placeholder' => 'Hasta', 'min' => 0]) !!}
</div>
<div class="form-group @if($errors->has('logo')) has-error @endif">
    {!! Form::label('logo', 'Logo (opcional)', ['class' => 'control-label']) !!}
	@if(isset($company) and $company->logo)
		<div class="text-center">
			<img src="{{ asset($company->logo) }}" class="img-thumbnail w-50">
		</div>
	@endif
    <div class="custom-file">
        <input type="file" class="custom-file-input" id="customFileLang" lang="es" name="logo">
        <label class="custom-file-label form-control-file" for="customFileLang">Seleccionar archivo</label>
    </div>
</div>