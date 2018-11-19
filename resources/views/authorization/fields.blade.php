<div class="form-group  @if($errors->has('code')) has-error @endif">
    {!! Form::label('code', 'Código', ['class' => 'control-label']) !!}
    {!! Form::text('code', old('code', isset($code) ? $code : ''), ['class' => 'form-control underlined', 'placeholder' => 'Código de la autorización (puede estar vacío)', isset($show) ? 'readonly' : '', 'id' => 'code']) !!}
    @if (isset($code))
        {!! Form::hidden('old_code', $code, ['id' => 'old_code']) !!}
    @endif
    <span class="text-danger" role="alert" id="check-authorization"><em></em></span>
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
                $services = \App\EpsService::getServices(old('eps_id') ?: $initialEpsId);
                if (count($services) < 1) {
                    $services = [
                        "0" => 'Sin servicios registrados'
                    ];
                }
            @endphp
            <div class="col-md-9">
                <div id="dynamic-services">
                    @include('partials._services')
                </div>
            </div>
            @if (!isset($authorization))
            <div class="col-md-3 text-right">
                <a href="/eps-services/{{ $initialEpsId }}/create-from-authorization" class="btn btn-oval btn-success" id="serviceLink">Nuevo</a>
            </div>
            @endif
        </div>
    </div>
@endif
<div class="form-group  @if($errors->has('diagnosis')) has-error @endif">
    {!! Form::label('diagnosis', 'Diagnóstico', ['class' => 'control-label']) !!}
    {!! Form::text('diagnosis', old('diagnosis', isset($authorization) ? $authorization->diagnosis : ''), ['class' => 'form-control underlined', 'placeholder' => 'Diagnóstico (opcional)']) !!}
</div>
<div class="form-group  @if($errors->has('location')) has-error @endif">
    {!! Form::label('location', 'Ubicación del paciente', ['class' => 'control-label']) !!}
    {!! Form::select('location', config('constants.patient.location'), old('location', isset($authorization) ? $authorization->location : ''), ['class' => 'form-control', isset($show) ? 'disabled' : '']) !!}
</div>
<div class="form-group  @if($errors->has('patient_phone')) has-error @endif">
    {!! Form::label('patient_phone', 'Número de contacto', ['class' => 'control-label']) !!}
    {!! Form::text('patient_phone', old('patient_phone', (isset($authorization) and $authorization->patient->phone) ? $authorization->patient->phone->phone : ''), ['class' => 'form-control underlined', 'placeholder' => 'Número de contacto (opcional)', 'maxlength' => 15]) !!}
</div>
