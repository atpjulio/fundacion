<div class="form-group  @if ($errors->has('phone')) has-error @endif">
  <label for="phone" class="control-label">Teléfono</label>
  <input type="text" name="phone" maxlength="15" class="form-control underlined"
    value="{{ old('phone', isset($phone) ? $phone->phone : '') }}" placeholder="Teléfono principal">
</div>
<div class="form-group  @if ($errors->has('phone2')) has-error @endif">
  <label for="phone2" class="control-label">Otro Teléfono (opcional)</label>
  <input type="text" name="phone2" maxlength="15" class="form-control underlined"
    value="{{ old('phone2', isset($phone) ? $phone->phone2 : '') }}" placeholder="Otro teléfono">
</div>
