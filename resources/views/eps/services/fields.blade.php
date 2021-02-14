<div class="form-group @if ($errors->has('code')) has-error @endif">
  <label for="code" class="control-label">Código</label>
  <input type="text" name="code" class="form-control underlined"
    value="{{ old('code', isset($service) ? $service->code : '') }}" placeholder="Código del servicio" id="code-group"
    focus>
</div>
<div class="form-group @if ($errors->has('name')) has-error @endif">
  <label for="name" class="control-label">Nombre</label>
  <input type="text" name="name" class="form-control underlined"
    value="{{ old('name', isset($service) ? $service->name : '') }}" placeholder="Nombre del servicio" maxlength="190"
    id="name-group">
</div>
<div class="form-group @if ($errors->has('price')) has-error @endif">
  <label for="price" class="control-label">Precio</label>
  <input type="number" name="price" id="price-group" class="form-control underlined"
    placeholder="Precio del servicio (opcional)"
    value="{{ old('price', (isset($service) and $service->price > 0) ? $service->price : ($eps->daily_price > 0 ? $eps->daily_price : $eps->price[0]->daily_price)) }}"
    min="1">
</div>
