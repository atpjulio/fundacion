<div class="row">
  <div class="col-md-6">
    <div class="form-group  @if ($errors->has('company_id')) has-error @endif">
      <label for="company_id" class="control-label">Seleccione la compañía</label>
      {!! Form::select('company_id', $companies, old('company_id', $oldCompanyId), ['class' => 'form-control']) !!}
    </div>
    <div class="row">
      <div class="col-6">
        <div class="form-group  @if ($errors->has('initial_number')) has-error @endif">
          <label for="initial_number" class="control-label">Factura inicial</label>
          <input type="number" name="initial_number" id="initial_number" class="form-control underlined"
            value="{{ old('initial_number', $initial_number) }}">
        </div>
      </div>
      <div class="col-6">
        <div class="form-group  @if ($errors->has('final_number')) has-error @endif">
          <label for="final_number" class="control-label">Factura final</label>
          <input type="number" name="final_number" id="final_number" class="form-control underlined"
            value="{{ old('final_number', $final_number) }}">
        </div>
      </div>
    </div>
  </div>
  <div class="col-md-6">
    <div class="form-group  @if ($errors->has('eps_id')) has-error @endif">
      <label for="eps_id" class="control-label">Seleccione EPS</label>
      {!! Form::select('eps_id', $epss, old('eps_id', $oldEpsId), ['class' => 'form-control']) !!}
    </div>
    <div class="row">
      <div class="col-6">
        <div class="form-group  @if ($errors->has('initial_date')) has-error @endif">
          <label for="initial_date" class="control-label">Fecha inicial</label>
          <input type="date" name="initial_date" placeholder="dd/mm/aaaa" class="form-control underlined" value="{{ old('initial_date', $initialDate ) }}" id="initial_date">
        </div>
      </div>
      <div class="col-6">
        <div class="form-group  @if ($errors->has('final_date')) has-error @endif">
          <label for="final_date" class="control-label">Fecha final</label>
          <input type="date" name="final_date" placeholder="dd/mm/aaaa" class="form-control underlined" value="{{ old('final_date', $finalDate ) }}" id="final_date">
        </div>
      </div>
    </div>
  </div>
  <div class="col-md-12">
    <div class="form-group  @if ($errors->has('selection')) has-error @endif">
      <label for="selection" class="control-label">Seleccione las facturas a exportar</label>
      {!! Form::select('selection[]', $selection, old('selection[]', $oldSelection), [
      'class' => 'form-control
      multiple-select',
      'multiple' => 'multiple',
      ]) !!}
    </div>
    <div class="text-center">
      <button type="submit" class="btn btn-oval btn-primary" name="search" value="search">
        <i class="fas fa-search"></i>
        Buscar
      </button>
    </div>
  </div>
</div>
