<div class="form-group @if($errors->has('gender')) has-error @endif">
  <label for="gender" class="control-label">Sexo</label>
  <select name="gender" class="form-control underlined">
    @foreach (config('enum.gender.text') as $value => $option)
      <option value="{{ $value }}" @if (old('gender', isset($patient) ? $patient->data->gender : '') == $value) selected @endif>
        {{ $option }}</option>
    @endforeach
  </select>
</div>

<div class="form-group @if($errors->has('type')) has-error @endif">
  <label for="type" class="control-label">Tipo de usuario</label>
  <select name="type" class="form-control underlined">
    @foreach (config('enum.patient.type.text') as $value => $option)
      <option value="{{ $value }}" @if (old('type', isset($patient) ? $patient->data->type : '') == $value) selected @endif>
        {{ $option }}</option>
    @endforeach
  </select>
</div>

<div class="form-group  @if($errors->has('zone')) has-error @endif">
  <label for="zone" class="control-label">Zona de residencia habitual</label>
  <select name="zone" class="form-control underlined">
    @foreach (config('enum.patient.zone.text') as $value => $option)
      <option value="{{ $value }}" @if (old('zone', isset($patient) ? $patient->data->zone : '') == $value) selected @endif>
        {{ $option }}</option>
    @endforeach
  </select>
</div>

<div class="form-group  @if ($errors->has('phone')) has-error @endif">
  <label for="phone" class="control-label">Teléfono</label>
  <input type="text" name="phone" maxlength="15" class="form-control underlined"
    value="{{ old('phone', isset($patient) ? $patient->phone : '') }}" placeholder="Teléfono de contacto">
</div>