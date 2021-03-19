<div class="row">
  <div class="col-md-6">
    <div class="form-group  @if ($errors->has('dni_type')) has-error @endif">
      <label for="dni_type" class="control-label">Tipo de documento *</label>
      <select name="dni_type" class="form-control underlined">
        @foreach (config('constants.companiesDocumentTypes') as $value => $option)
          <option value="{{ $value }}" @if (old('dni_type', isset($merchant) ? $merchant->dni_type : '') == $value) selected @endif>
            {{ $option }}</option>
        @endforeach
      </select>
    </div>
  </div>
  <div class="col-md-6">
    <div class="form-group @if ($errors->has('dni')) has-error @endif">
      <label for="dni" class="control-label">Número de documento *</label>
      <input type="text" name="dni" class="form-control underlined"
        value="{{ old('dni', isset($merchant) ? $merchant->dni : '') }}" placeholder="Número de documento">
    </div>
  </div>
</div>

<div class="form-group @if ($errors->has('name')) has-error @endif">
  <label for="name" class="control-label">Nombre fiscal *</label>
  <input type="text" name="name" class="form-control underlined"
    value="{{ old('name', isset($merchant) ? $merchant->name : '') }}" placeholder="Nombre fiscal">
</div>

<div class="form-group @if ($errors->has('alias')) has-error @endif">
  <label for="alias" class="control-label">Nombre corto o alias</label>
  <input type="text" name="alias" class="form-control underlined"
    value="{{ old('alias', isset($merchant) ? $merchant->alias : '') }}" placeholder="Nombre corto o alias">
</div>

@isset($merchant)    
<div class="form-group @if ($errors->has('image')) has-error @endif">
  <label for="image" class="control-label">Imagen (opcional)</label>
  <div class="text-center">
    <img src="{{ asset($merchant->imageUrl ?? '/img/no-image.png') }}" class="img-thumbnail w-50 mb-4" id="customFileImage">
  </div>
  <div class="custom-file">
    <input type="file" class="custom-file-input" id="customFileLang" lang="es" name="file">
    <label class="custom-file-label form-control-file" for="customFileLang">Seleccionar archivo</label>
  </div>
</div>
@endisset

@if (!isset($merchant))
  <div class="row">
    <div class="col-md-6">
      <div class="form-group @if ($errors->has('resolution')) has-error @endif">
        <label for="resolution" class="control-label">Resolución de factura</label>
        <input type="text" name="resolution" class="form-control underlined" value="{{ old('resolution') }}"
          placeholder="Resolución de factura">
      </div>
    </div>
    <div class="col-md-6">
      <div class="form-group @if ($errors->has('resolution_date')) has-error @endif">
        <label for="resolution_date" class="control-label">Fecha de resolución</label>
        <input type="date" name="resolution_date" class="form-control underlined" value="{{ old('resolution_date') }}"
          placeholder="Prefijo de factura">
      </div>
    </div>
    <div class="col-md-6">
      <div class="form-group @if ($errors->has('prefix')) has-error @endif">
        <label for="prefix" class="control-label">Prefijo de factura</label>
        <input type="text" name="prefix" class="form-control underlined" value="{{ old('prefix') }}"
          placeholder="Prefijo de factura">
      </div>
    </div>
    <div class="col-md-6">
      <div class="form-group @if ($errors->has('number')) has-error @endif">
        <label for="number" class="control-label">Comenzar con el número *</label>
        <input type="number" name="number" class="form-control underlined" value="{{ old('number') }}"
          placeholder="Ej: 1">
      </div>
    </div>
    <div class="col-md-6">
      <div class="form-group @if ($errors->has('number_from')) has-error @endif">
        <label for="number_from" class="control-label">Desde el número *</label>
        <input type="number" name="number_from" class="form-control underlined" value="{{ old('number_from') }}"
          placeholder="Ej: 1">
      </div>
    </div>
    <div class="col-md-6">
      <div class="form-group @if ($errors->has('number_to')) has-error @endif">
        <label for="number_to" class="control-label">Hasta el número *</label>
        <input type="number" name="number_to" class="form-control underlined" value="{{ old('number_to') }}"
          placeholder="Ej: 1000">
      </div>
    </div>
  </div>
@endif

@push('scripts')
  <script src="{{ asset('js/shared/fileInput.js') }}"></script>
@endpush
