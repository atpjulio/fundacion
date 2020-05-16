<div class="row">
	<div class="col-md-6">
		<div class="form-group  @if($errors->has('initial_date')) has-error @endif">
			{!! Form::label('initial_date', 'Fecha de remisión desde', ['class' => 'control-label']) !!}
			{!! Form::date('initial_date', old('initial_date', isset($rip) ? $rip->initial_date : \Carbon\Carbon::now()), ['class'
			=> 'form-control underlined', 'placeholder' => 'dd/mm/aaaa', isset($show) ? 'readonly' : '']) !!}
		</div>
	</div>
	<div class="col-md-6">
		<div class="form-group  @if($errors->has('final_date')) has-error @endif">
			{!! Form::label('final_date', 'Fecha de remisión hasta', ['class' => 'control-label']) !!}
			{!! Form::date('final_date', old('final_date', isset($rip) ? $rip->final_date :
			\Carbon\Carbon::now()->addMonth()), ['class' => 'form-control underlined', 'placeholder' => 'dd/mm/aaaa',
			isset($show) ? 'readonly' : '']) !!}
		</div>
	</div>
</div>

<div id="dynamic-invoice-amount">
	<div class="form-group">
		{!! Form::label('hint', 'Facturas disponibles para EPS', ['class' => 'control-label']) !!}
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