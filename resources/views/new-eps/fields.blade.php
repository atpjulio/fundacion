<div class="form-group  @if ($errors->has('merchant_id')) has-error @endif">
  <label for="merchant_id" class="control-label">Empresa *</label>
  <select name="merchant_id" id="merchant_id" class="form-control">
    @foreach ($merchants as $merchant)
      <option value="{{ $merchant->value }}" @if (old('merchant_id', isset($eps) ? $eps->merchant_id : '') == $merchant->value) ) selected @endif>
        {!! $merchant->name !!}
      </option>
    @endforeach
  </select>
</div>

<div class="row">
  <div class="col-md-6">
    <div class="form-group @if ($errors->has('nit')) has-error @endif">
      <label for="nit" class="control-label">NIT *</label>
      <input type="text" name="nit" class="form-control underlined"
        value="{{ old('nit', isset($eps) ? $eps->nit : '') }}" placeholder="NIT">
    </div>
  </div>
  <div class="col-md-6">
    <div class="form-group @if ($errors->has('code')) has-error @endif">
      <label for="code" class="control-label">Código *</label>
      <input type="text" name="code" class="form-control underlined"
        value="{{ old('code', isset($eps) ? $eps->code : '') }}" placeholder="Código">
    </div>
  </div>
</div>

<div class="form-group @if ($errors->has('name')) has-error @endif">
  <label for="name" class="control-label">Nombre fiscal *</label>
  <input type="text" name="name" class="form-control underlined"
    value="{{ old('name', isset($eps) ? $eps->name : '') }}" placeholder="Nombre fiscal">
</div>

<div class="form-group @if ($errors->has('alias')) has-error @endif">
  <label for="alias" class="control-label">Nombre corto o alias</label>
  <input type="text" name="alias" class="form-control underlined"
    value="{{ old('alias', isset($eps) ? $eps->alias : '') }}" placeholder="Nombre corto o alias">
</div>

<div class="row">
  <div class="col-md-6">
    <div class="form-group @if ($errors->has('contract_number')) has-error @endif">
      <label for="contract_number" class="control-label">Número de contrato</label>
      <input type="text" name="contract_number" class="form-control underlined"
        value="{{ old('contract_number', isset($eps) ? $eps->contract_number : '') }}"
        placeholder="Número de contrato">
    </div>
  </div>
  <div class="col-md-6">
    <div class="form-group @if ($errors->has('policy')) has-error @endif">
      <label for="policy" class="control-label">Póliza</label>
      <input type="text" name="policy" class="form-control underlined"
        value="{{ old('policy', isset($eps) ? $eps->policy : '') }}" placeholder="Póliza">
    </div>
  </div>
</div>
