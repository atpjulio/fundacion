<div class="form-group  @if ($errors->has('code')) has-error @endif">
  <label for="code" class="control-label">Código</label>
  <input type="text" name="code" class="form-control underlined"
    value="{{ old('code', isset($eps) ? $eps->code : '') }}" placeholder="Código de la EPS">
</div>
<div class="form-group  @if ($errors->has('name')) has-error @endif">
  <label for="name" class="control-label">Nombre</label>
  <input type="text" name="name" class="form-control underlined"
    value="{{ old('name', isset($eps) ? $eps->name : '') }}" placeholder="Nombre de la EPS">
</div>
<div class="form-group  @if ($errors->has('nit')) has-error @endif">
  <label for="nit" class="control-label">NIT</label>
  <input type="text" name="nit" class="form-control underlined" value="{{ old('nit', isset($eps) ? $eps->nit : '') }}"
    placeholder="NIT">
</div>
<div class="form-group  @if ($errors->has('alias')) has-error @endif">
  <label for="alias" class="control-label">Nombre corto</label>
  <input type="text" name="alias" class="form-control underlined"
    value="{{ old('alias', isset($eps) ? $eps->alias : '') }}" placeholder="Nombre corto para usarlo en el sistema">
</div>
<div class="form-group  @if ($errors->has('contract')) has-error @endif">
  <label for="contract" class="control-label">Número de contrato</label>
  <input type="text" name="contra" class="form-control underlined"
    value="{{ old('contract', isset($eps) ? $eps->contract : '') }}" placeholder="Número de Contrato">
</div>
<div class="form-group  @if ($errors->has('policy')) has-error @endif">
  <label for="policy" class="control-label">Póliza</label>
  <input type="text" name="policy" class="form-control underlined"
    value="{{ old('policy', isset($eps) ? $eps->policy : '') }}" placeholder="Póliza">
</div>
