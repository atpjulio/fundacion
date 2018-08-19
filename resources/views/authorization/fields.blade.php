<div class="form-group  @if($errors->has('code')) has-error @endif">
    {!! Form::label('code', 'Código', ['class' => 'control-label']) !!}
    {!! Form::text('code', old('code', isset($code) ? $code : ''), ['class' => 'form-control underlined', 'placeholder' => 'Código de la autorización', isset($show) ? 'readonly' : '']) !!}
</div>
@if (isset($show))
    <div class="form-group  @if($errors->has('eps_name')) has-error @endif">
        {!! Form::label('eps_name', 'Nombre de EPS', ['class' => 'control-label']) !!}
        {!! Form::text('eps_name', old('eps_name', isset($eps) ? $eps->name : ''), ['class' => 'form-control underlined', 'placeholder' => 'Nombre de EPS', 'readonly']) !!}
        {!! Form::hidden('eps_id', $eps->id) !!}
    </div>
@else
    <div class="form-group  @if($errors->has('eps_id')) has-error @endif">
        {!! Form::label('eps_id', 'Seleccione EPS', ['class' => 'control-label']) !!}
        {!! Form::select('eps_id', $epss, old('eps_id', isset($authorization) ? $authorization->eps_id : ''), ['class' => 'form-control', 'id' => 'epsSelect']) !!}
    </div>
@endif

@if (isset($show))
    <div class="form-group  @if($errors->has('service_name')) has-error @endif">
        {!! Form::label('service_name', 'Servicio autorizado', ['class' => 'control-label']) !!}
        {!! Form::text('service_name', old('service_name', isset($service) ? $service->name : ''), ['class' => 'form-control underlined', 'placeholder' => 'Servicio de EPS', 'readonly']) !!}
        {!! Form::hidden('eps_service_id', $service->id) !!}
    </div>
@else
    <div class="form-group  @if($errors->has('eps_service_id')) has-error @endif">
        {!! Form::label('eps_service_id', 'Seleccione Servicio de EPS', ['class' => 'control-label']) !!}
        <div class="row">
            @php
                $services = \App\EpsService::getServices(old('eps_id') ?: $initialEpsId)->pluck('name', 'id');
                if (count($services) < 1) {
                    $services = [
                        "0" => 'Sin servicios registrados'
                    ];
                }
            @endphp
            <div class="col-md-9">
                <div id="dynamic-services">
                    {!! Form::select('eps_service_id', $services, old('eps_service_id', isset($authorization) ? $authorization->eps_service_id : ''), ['class' => 'form-control', 'style' => $errors->has("eps_service_id") ? 'border: 1px solid red !important' : '']) !!}
                </div>
            </div>
            <div class="col-md-3 text-right">
                <a href="/eps-services/{{ $initialEpsId }}/create-from-authorization" class="btn btn-oval btn-success" id="serviceLink">Nuevo</a>
            </div>
        </div>
    </div>
@endif