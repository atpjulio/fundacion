<div id="dynamic-egress-amount">                            
    <div class="form-group">
        {!! Form::label('hint', 'Comprobantes de Egreso disponibles', ['class' => 'control-label']) !!}
        @if (isset($egressesAmount) and $egressesAmount > 0) 
            <div class="alert alert-dark" role="alert">
                Total: {!! $egressesAmount !!} comprobante(s) de egreso disponible(s) para el mes y año seleccionado
            </div>
        @else
            <div class="alert alert-warning" role="alert" style="background-color: #fff3cd; color: #856404;">
                No hay comprobantes de egreso disponibles para el mes y año seleccionado
            </div>
        @endif
    </div>
</div>
