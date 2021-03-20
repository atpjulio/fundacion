<div class="row">

  <div class="col-md-6">
    <div class="form-group @if ($errors->has('status')) has-error @endif">
      <label for="status" class="control-label">Estado del servicio *</label>
      <select name="status" class="form-control underlined">
        @foreach (config('enum.status') as $status)
          <option value="{{ $status }}" @if (old('status', isset($service) ? $service->status : '') == $status) selected @endif>
            {{ __('status.' . $status) }}</option>
        @endforeach
      </select>
    </div>
  </div>

  <div class="col-md-6">
    <div class="form-group @if ($errors->has('code')) has-error @endif">
      <label for="code" class="control-label">Código del servicio *</label>
      <input type="text" name="code" class="form-control underlined"
        value="{{ old('code', isset($service) ? $service->code : '') }}" placeholder="Código del servicio">
    </div>
  </div>

  <div class="col-md-12">
    <div class="form-group @if ($errors->has('name')) has-error @endif">
      <label for="name" class="control-label">Nombre del servicio *</label>
      <input type="text" name="name" class="form-control underlined"
        value="{{ old('name', isset($service) ? $service->name : '') }}" placeholder="Nombre del servicio">
    </div>
  </div>

  <div class="col-md-12">
    <div class="form-group @if ($errors->has('amount')) has-error @endif">
      <label for="amount" class="control-label">Monto *</label>
      <input type="number" name="amount" class="form-control underlined"
        value="{{ old('amount', isset($service) ? $service->amount : '') }}" placeholder="Ej: 50000">
    </div>
  </div>

</div>
