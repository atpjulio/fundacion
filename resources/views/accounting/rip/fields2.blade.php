<div class="form-group">
    {!! Form::label('hint', 'Facturas disponibles para EPS', ['class' => 'control-label']) !!}
    @if ($invoicesAmount > 0) 
	    <div class="alert alert-dark" role="alert">
  			Total: {!! $invoicesAmount !!} factura(s) disponible(s) para el rango de fecha seleccionado
		</div>
	@else
	    <div class="alert alert-warning" role="alert" style="background-color: #fff3cd; color: #856404;">
  			No hay facturas disponibles para el rango de fecha seleccionado
		</div>
	@endif
</div>

