<div class="form-group  @if ($errors->has('location')) has-error @endif">
  <label for="location" class="control-label">Ubicación del paciente</label>
  {!! Form::select('location', config('constants.patient.location'), old('location', isset($authorization) ?
  $authorization->location : ''), ['class' => 'form-control', isset($show) ? 'disabled' : '']) !!}
</div>
<div class="row">
  <div class="col-md-6">
    <div class="form-group  @if ($errors->has('patient_phone')) has-error @endif">
      <label for="patient_phone" class="control-label">Número de contacto</label>
      <input type="text" name="patient_phone" class="form-control underlined"
        value="{{ old('patient_phone', (isset($authorization) and $authorization->patient->phone) ? $authorization->patient->phone->phone : '') }}"
        placeholder="Número de contacto (opcional)" maxlength="15">
    </div>
  </div>
  <div class="col-md-6">
    <div class="form-group  @if ($errors->has('diagnosis')) has-error @endif">
      <label for="diagnosis" class="control-label">Diagnóstico</label>
      <input type="text" name="diagnosis" class="form-control underlined"
        value="{{ old('diagnosis', isset($authorization) ? $authorization->diagnosis : '') }}"
        placeholder="Diagnóstico (opcional)">
    </div>
  </div>
</div>

<div class="form-group  @if ($errors->has('date_from')) has-error @endif">
  <label for="date_from" class="control-label">Fecha de inicio</label>
  <input type="date" name="date_from" value="{{ old('date_from', isset($dateFrom) ? $dateFrom : now()) }}"
    class="form-control underlined" placeholder="dd/mm/aaaa'" @isset($show) readonly @endisset>
</div>
<div class="form-group  @if ($errors->has('total_days')) has-error @endif">
  <label for="total_days" class="control-label">Cantidad</label>
  <input type="number" name="total_days" class="form-control underlined"
    value="{{ old('total_days', isset($authorization) ? $authorization->days : 1) }}" placeholder="1" @isset($show)
    readonly @endisset min="1" id="total_days">
</div>
