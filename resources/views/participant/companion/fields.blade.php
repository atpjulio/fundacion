<div class="form-group  @if ($errors->has('eps_id')) has-error @endif">
  <label for="eps_id" class="control-label">EPS *</label>
  <select name="eps_id" id="eps_id" class="form-control underlined">
    @foreach ($epss as $eps)
      <option value="{{ $eps->value }}" @if (old('eps_id', isset($eps) ? $eps->eps_id : '') == $eps->value) ) selected @endif>
        {!! $eps->name !!}
      </option>
    @endforeach
  </select>
</div>

<div class="form-group @if ($errors->has('dni_type')) has-error @endif">
  <label for="dni_type" class="control-label">Tipo de documento *</label>
  <select name="dni_type" class="form-control underlined">
    @foreach (config('constants.documentTypes') as $value => $option)
      <option value="{{ $value }}" @if (old('dni_type', isset($companion) ? $companion->dni_type : '') == $value) selected @endif>
        {{ $option }}</option>
    @endforeach
  </select>
</div>
<div class="form-group @if ($errors->has('dni')) has-error @endif">
  <label for="dni" class="control-label">Número de documento *</label>
  <input type="text" name="dni" class="form-control underlined"
    value="{{ old('dni', isset($companion) ? $companion->dni : '') }}" placeholder="Número de documento">
</div>

<div class="form-group @if ($errors->has('first_name')) has-error @endif">
  <label for="first_name" class="control-label">Nombres *</label>
  <input type="text" name="first_name" class="form-control underlined"
    value="{{ old('first_name', isset($companion) ? $companion->first_name : '') }}" placeholder="Nombres">
</div>

<div class="form-group @if ($errors->has('last_name')) has-error @endif">
  <label for="last_name" class="control-label">Apellidos *</label>
  <input type="text" name="last_name" class="form-control underlined"
    value="{{ old('last_name', isset($companion) ? $companion->last_name : '') }}" placeholder="Apellidos">
</div>

<div class="form-group @if ($errors->has('phone')) has-error @endif">
  <label for="phone" class="control-label">Teléfono</label>
  <input type="text" name="phone" class="form-control underlined"
    value="{{ old('phone', isset($companion) ? $companion->phone : '') }}" placeholder="Teléfono">
</div>
