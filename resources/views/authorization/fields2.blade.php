<div class="form-group  @if($errors->has('location')) has-error @endif">
    {!! Form::label('location', 'Ubicación del paciente', ['class' => 'control-label']) !!}
    {!! Form::select('location', config('constants.patient.location'), old('location', isset($authorization) ? $authorization->location : ''), ['class' => 'form-control', isset($show) ? 'disabled' : '']) !!}
</div>
<div class="row">
    <div class="col-md-6">
        <div class="form-group  @if($errors->has('patient_phone')) has-error @endif">
            {!! Form::label('patient_phone', 'Número de contacto', ['class' => 'control-label']) !!}
            {!! Form::text('patient_phone', old('patient_phone', (isset($authorization) and $authorization->patient->phone) ? $authorization->patient->phone->phone : ''), ['class' => 'form-control underlined', 'placeholder' => 'Número de contacto (opcional)', 'maxlength' => 15]) !!}
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group  @if($errors->has('diagnosis')) has-error @endif">
            {!! Form::label('diagnosis', 'Diagnóstico', ['class' => 'control-label']) !!}
            {!! Form::text('diagnosis', old('diagnosis', isset($authorization) ? $authorization->diagnosis : ''), ['class' => 'form-control underlined', 'placeholder' => 'Diagnóstico (opcional)']) !!}
        </div>
    </div>
</div>

<div class="form-group  @if($errors->has('date_from')) has-error @endif">
    {!! Form::label('date_from', 'Fecha de inicio', ['class' => 'control-label']) !!}
    {!! Form::date('date_from', old('date_from', isset($dateFrom) ? $dateFrom : ''), ['class' => 'form-control underlined', 'placeholder' => 'dd/mm/aaaa', isset($show) ? 'readonly' : '']) !!}
</div>
<div class="form-group  @if($errors->has('total_days')) has-error @endif">
    {!! Form::label('total_days', 'Cantidad', ['class' => 'control-label']) !!}
    {!! Form::number('total_days', old('total_days', isset($authorization) ? $authorization->days : 1), ['class' => 'form-control underlined', 'placeholder' => '0', isset($show) ? 'readonly' : '', 'min' => 1, 'id' => 'total_days']) !!}
</div>
