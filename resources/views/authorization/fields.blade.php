<div class="form-group  @if ($errors->has('code')) has-error @endif">
  <label for="code" class="control-label">Código</label>
  <input type="text" name="code" class="form-control underlined"
    placeholder="Código de la autorización (puede estar vacío)" value="{{ old('code', isset($code) ? $code : '') }}"
    @isset($show) readonly @endisset id="code">
  @if (isset($code))
    <input type="hidden" name="old_code" value="{{ $code }}" id="old_code">
  @endif
  <span class="text-danger" role="alert" id="check-authorization"><em></em></span>
</div>
@if (isset($show))
  <div class="form-group  @if ($errors->has('eps_name')) has-error @endif">
    <label for="eps_name" class="control-label">Nombre de EPS</label>
    <input type="text" name="eps_name" class="form-control underlined" placeholder="Nombre de EPS"
      value="{{ old('eps_name', isset($eps) ? $eps->name : '') }}" readonly>
    <input type="hidden" name="eps_id" value="{{ $eps->id }}">
  </div>
@else
  <div class="form-group  @if ($errors->has('eps_id')) has-error @endif">
    <label for="eps_id" class="control-label">Seleccione EPS</label>
    <select name="eps_id" id="epsSelect" class="form-control">
      @foreach ($epss as $id => $name)
        <option value="{{ $id }}" @if (old('eps_id', isset($authorization) ? $authorization->eps_id : '') == $id) selected @endif>{{ $name }}</option>
      @endforeach
    </select>
  </div>
@endif

@php
$services = \App\EpsService::getServices(old('eps_id') ?: $initialEpsId);
if (count($services) < 1) {
    $services = [
        '0' => 'Sin servicios registrados',
    ];
}
@endphp
@if (isset($show))
  <div class="form-group  @if ($errors->has('service_name')) has-error @endif">
    <label for="service_name" class="control-label">Servicio autorizado</label>
    <input type="text" name="service_name" class="form-control underlined" placeholder="Servicio de EPS"
      value="{{ old('service_name', isset($service) ? $service->name : '') }}" readonly>
    <input type="hidden" name="eps_service_id" value="{{ $service->id }}">
  </div>
@else
  <div class="form-group  @if ($errors->has('eps_service_id')) has-error @endif">
    <label for="eps_service_id" class="control-label">Seleccione Servicio de EPS</label>
    <div class="row">
      <div class="col-md-9">
        <div id="dynamic-services">
          @include('partials._services')
        </div>
      </div>
      @if (!isset($authorization))
        <div class="col-md-3 text-right">
          <a href="javascript:showModal('new-service/{{ $initialEpsId }}')" class="btn btn-oval btn-success"
            id="serviceLink">
            Nuevo
          </a>
        </div>
      @endif
    </div>
  </div>
@endif
<div class="form-group  @if ($errors->has('daily_price')) has-error @endif">
  <label for="daily_price" class="control-label">Tarifa diaria</label>
  <div id="dynamic-daily-prices">
    @include('partials._daily_prices')
  </div>
</div>
