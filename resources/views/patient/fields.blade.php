@if (!isset($show))
  <div class="form-group  @if ($errors->has('eps_id')) has-error @endif">
    <label for="eps_id" class="control-label">EPS a la que pertenece el usuario</label>
    <select name="eps_id" class="form-control">
      @foreach ($epss as $id => $name)
        <option value="{{ $id }}" @if (old('eps_id', isset($patient) ? $patient->eps_id : '') == $id) selected @endif>
          {{ $name }}</option>
      @endforeach
    </select>
  </div>
@endif
<div class="form-group  @if ($errors->has('dni_type')) has-error @endif">
  <label for="dni_type" class="control-label">Tipo de Documento</label>
  <select name="dni_type" class="form-control" @isset($show) disabled @endisset>
    @foreach (config('constants.documentTypes') as $value => $option)
      <option value="{{ $value }}" @if (old('dni_type', isset($patient) ? $patient->dni_type : '') == $value) selected @endif>
        {{ $option }}</option>
    @endforeach
  </select>

</div>
<div class="form-group @if ($errors->has('dni')) has-error @endif">
  <label for="dni" class="control-label">Número de documento</label>
  <input type="text" name="dni" class="form-control underlined" id="dni" @isset($show) readonly @endisset
    value="{{ old('dni', isset($patient) ? $patient->dni : '') }}" placeholder="Número de documento">
  @if (isset($patient))
    <input type="hidden" name="old_dni" value="{{ $patient->dni }}" id="old_dni">
  @endif
  <span class="text-danger" role="alert" id="check-patient"><em></em></span>
</div>
<div class="form-group @if ($errors->has('last_name')) has-error @endif">
  <label for="last_name" class="control-label">Apellido(s)</label>
  <input type="text" name="last_name" class="form-control underlined" @isset($show) readonly @endisset
    value="{{ old('last_name', isset($patient) ? $patient->last_name : '') }}" placeholder="Apellido(s)">
</div>
<div class="form-group @if ($errors->has('first_name')) has-error @endif">
  <label for="first_name" class="control-label">Nombre(s)</label>
  <input type="text" name="first_name" class="form-control underlined" @isset($show) readonly @endisset
    value="{{ old('first_name', isset($patient) ? $patient->first_name : '') }}" placeholder="Nombre(s)">
</div>
<div class="form-group @if ($errors->has('birth_date')) has-error @endif">
  <label for="birth_date" class="control-label">Fecha de Nacimiento</label>
</div>
<div class="form-inline">
  <div class="form-group  @if ($errors->has('birth_date')) has-error @endif">
    <select name="birth_year" id="birth_year" class="form-control" @isset($show) disabled @endisset>
      @foreach ($i = 1918; $i <= date('Y'); $i++)
        <option value="{{ $i }}" @if (old('birth_year', isset($patient) ? intval(substr($patient->birth_date, 0, 4)) : 1970) == $i) selected @endif>
          {{ $i }}
          </option>
      @endforeach
    </select>
    &nbsp;&nbsp;
    <select name="birth_month" id="birth_month" class="form-control" @isset($show) disabled @endisset>
      @foreach (config('constants.months') as $value => $option)
        <option value="{{ $value }}" @if (old('birth_month', isset($patient) ? intval(substr($patient->birth_date, 5, 2)) : 1) == $value) selected @endif>
          {{ $option }}
          </option>
      @endforeach
    </select>    
    &nbsp;&nbsp;
    <div id="dynamic-days">
      <select name="birth_day" id="birth_day" class="form-control" @isset($show) disabled @endisset>
        @foreach ($i = 1; $i <= 28; $i++)
          <option value="{{ $i }}" @if (old('birth_day', isset($patient) ? intval(substr($patient->birth_date, 8, 2)) : 1) == $i) selected @endif>
            {{ $i }}
            </option>
        @endforeach
      </select>  
    </div>
  </div>
</div>
<br>
