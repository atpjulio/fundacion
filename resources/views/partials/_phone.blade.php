<div class="form-group  @if ($errors->has('phone1')) has-error @endif">
  <label for="phone1" class="control-label">Teléfono</label>
  <input type="text" name="phone1" maxlength="15" class="form-control underlined"
    value="{{ old('phone1', isset($phone) ? $phone->phone1 : '') }}" placeholder="Teléfono principal">
</div>
<div class="form-group  @if ($errors->has('phone2')) has-error @endif">
  <label for="phone2" class="control-label">Otro Teléfono</label>
  <input type="text" name="phone2" maxlength="15" class="form-control underlined"
    value="{{ old('phone2', isset($phone) ? $phone->phone2 : '') }}" placeholder="Otro teléfono">
</div>
