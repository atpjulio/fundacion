<div class="row">
  <div class="col-md-6">
    <div class="form-group @if ($errors->has('name')) has-error @endif">
      <label for="name" class="control-label">Nombre de la serie *</label>
      <input type="text" name="name" class="form-control underlined" value="{{ old('name', isset($serie) ? $serie->name : '') }}"
        placeholder="Resolución de factura">
    </div>
  </div>
  <div class="col-md-6">
    <div class="form-group @if ($errors->has('status')) has-error @endif">
      <label for="status" class="control-label">Estado de la serie *</label>
      <select name="status" class="form-control">
        @foreach (config('enum.status') as $status)
          <option value="{{ $status }}" @if (old('status', isset($serie) ? $serie->status : '') == $status) selected @endif>
            {{ __('status.'.$status) }}</option>
        @endforeach
      </select>
    </div>
  </div>
  <div class="col-md-6">
    <div class="form-group @if ($errors->has('resolution')) has-error @endif">
      <label for="resolution" class="control-label">Resolución de factura</label>
      <input type="text" name="resolution" class="form-control underlined" value="{{ old('resolution', isset($serie) ? $serie->resolution : '') }}"
        placeholder="Resolución de factura">
    </div>
  </div>
  <div class="col-md-6">
    <div class="form-group @if ($errors->has('resolution_date')) has-error @endif">
      <label for="resolution_date" class="control-label">Fecha de resolución</label>
      <input type="date" name="resolution_date" class="form-control underlined" value="{{ old('resolution_date', isset($serie) ? $serie->resolution_date : '') }}"
        placeholder="Prefijo de factura">
    </div>
  </div>
  <div class="col-md-6">
    <div class="form-group @if ($errors->has('prefix')) has-error @endif">
      <label for="prefix" class="control-label">Prefijo de factura</label>
      <input type="text" name="prefix" class="form-control underlined" value="{{ old('prefix', isset($serie) ? $serie->prefix : '') }}"
        placeholder="Prefijo de factura">
    </div>
  </div>
  <div class="col-md-6">
    <div class="form-group @if ($errors->has('number')) has-error @endif">
      <label for="number" class="control-label">Comenzar con el número *</label>
      <input type="number" name="number" class="form-control underlined" value="{{ old('number', isset($serie) ? $serie->number : '') }}"
        placeholder="Ej: 1">
    </div>
  </div>
  <div class="col-md-6">
    <div class="form-group @if ($errors->has('number_from')) has-error @endif">
      <label for="number_from" class="control-label">Desde el número *</label>
      <input type="number" name="number_from" class="form-control underlined" value="{{ old('number_from', isset($serie) ? $serie->number_from : '') }}"
        placeholder="Ej: 1">
    </div>
  </div>
  <div class="col-md-6">
    <div class="form-group @if ($errors->has('number_to')) has-error @endif">
      <label for="number_to" class="control-label">Hasta el número *</label>
      <input type="number" name="number_to" class="form-control underlined" value="{{ old('number_to', isset($serie) ? $serie->number_to : '') }}"
        placeholder="Ej: 1000">
    </div>
  </div>
</div>