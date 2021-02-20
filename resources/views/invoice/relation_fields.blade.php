<div class="form-group  @if ($errors->has('company_id')) has-error @endif">
  <label for="company_id" class="control-label">Seleccione la compañía</label>
  <select name="company_id" class="form-control">
    @foreach ($companies as $id => $name)
      <option value="{{ $id }}" @if (old('company_id', isset($rip) ? $rip->company_id : '') == $id) selected @endif>
        {{ $name }}</option>
    @endforeach
  </select>
</div>

@if (isset($show))
  <div class="form-group  @if ($errors->has('eps_name')) has-error @endif">
    <label for="eps_name" class="control-label">Nombre de EPS</label>
    <input type="text" name="eps_name" class="form-control underlined" placeholder="Nombre de EPS"
      value="{{ old('eps_name', isset($rip) ? $rip->name : '') }}">
    <input type="hidden" name="eps_id" value="{{ $rip->eps_id }}">
  </div>
@else
  <div class="form-group  @if ($errors->has('eps_id')) has-error @endif">
    <label for="eps_id" class="control-label">Seleccione EPS</label>
    <select name="eps_id" id="eps_id" class="form-control">
      @foreach ($epss as $id => $name)
        <option value="{{ $id }}" @if (old('eps_id', isset($rip) ? $rip->eps_id : '') == $id) selected @endif>
          {{ $name }}</option>
      @endforeach
    </select>
  </div>
@endif
<div class="row">
  <div class="col-6">
    <div class="form-group  @if ($errors->has('initial_number')) has-error @endif">
      <label for="initial_number" class="control-label">Factura inicial</label>
      <input type="number" name="initial_number" id="initial_number" class="form-control underlined" min="1"
        @isset($show) readonly @endisset value="{{ old('initial_number', $initial_number) }}">
    </div>
  </div>
  <div class="col-6">
    <div class="form-group  @if ($errors->has('final_number')) has-error @endif">
      <label for="final_number" class="control-label">Factura final</label>
      <input type="number" name="final_number" id="final_number" class="form-control underlined" min="1" @isset($show)
        readonly @endisset value="{{ old('final_number', $final_number) }}">
    </div>
  </div>
</div>
