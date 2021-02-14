<div class="form-group @if ($errors->has('name')) has-error @endif">
  <label for="name" class="control-label">Nombre de la entidad o persona</label>
  <input type="text" name="name" class="form-control underlined" id="name" value="{{ old('name', isset($entity) ? $entity->name : '') }}">
</div>
<div class="form-group @if ($errors->has('doc')) has-error @endif">
  <label for="doc" class="control-label">NIT / CC</label>
  <input type="text" name="doc" class="form-control underlined" id="doc" value="{{ old('doc', isset($entity) ? $entity->doc : '') }}">
</div>
<div class="form-group @if ($errors->has('address')) has-error @endif">
  <label for="address" class="control-label">Dirección</label>
  <input type="text" name="addres" class="form-control underlined" id="address" value="{{ old('address', isset($entity) ? $entity->address : '') }}">
</div>
<div class="form-group @if ($errors->has('phone')) has-error @endif">
  <label for="phone" class="control-label">Teléfono</label>
  <input type="text" name="phone" class="form-control underlined" id="phone" value="{{ old('phone', isset($entity) ? $entity->phone : '') }}">
</div>
