<div class="form-group  @if ($errors->has('company_id')) has-error @endif">
  <label for="company_id" class="control-label">Seleccione la compañía</label>
  {!! Form::select('company_id', $companies, old('company_id', isset($rip) ? $rip->company_id : ''), ['class' =>
  'form-control']) !!}
</div>

@if (isset($show))
  <div class="form-group  @if ($errors->has('eps_name')) has-error @endif">
    <label for="eps_name" class="control-label">Nombre de EPS</label>
    <input type="text" name="eps_name" value="{{ old('eps_name', isset($rip) ? $rip->name : '') }}"
      class="form-control underlined" placeholder="Nombre de EPS" readonly>
    <input type="hidden" name="eps_id" value="{{ $rip->eps_id }}">
  </div>
@else
  <div class="form-group  @if ($errors->has('eps_id')) has-error @endif">
    <label for="eps_id" class="control-label">Seleccione EPS</label>
    {!! Form::select('eps_id', $epss, old('eps_id', isset($rip) ? $rip->eps_id : ''), ['class' => 'form-control', 'id'
    => 'eps_id']) !!}
  </div>
@endif

<div class="row">
  <div class="col-6">
    <div class="form-group  @if ($errors->has('initial_number')) has-error @endif">
      <label for="initial_number" class="control-label">Factura inicial</label>
      {!! Form::number('initial_number', old('initial_number', $initialNumber), ['class' => 'form-control underlined',
      'id' => 'initial_number', isset($show) ? 'readonly' : '', 'min' => '1']) !!}
    </div>
  </div>
  <div class="col-6">
    <div class="form-group  @if ($errors->has('final_number')) has-error @endif">
      <label for="final_number" class="control-label">Factura final</label>
      {!! Form::number('final_number', old('final_number', $finalNumber), ['class' => 'form-control underlined', 'id' =>
      'final_number', isset($show) ? 'readonly' : '', 'min' => '1']) !!}
    </div>
  </div>
</div>
