<div class="form-group  @if ($errors->has('state_id')) has-error @endif">
  <label for="state_id" class="control-label">Departamento</label>
  <select name="state_id" id="state_id" class="form-control underlined"
    data-state-id="{{ old('state_id', isset($address) ? $address->state_id : '') }}"
    data-city-id="{{ old('city_id', isset($address) ? $address->city_id : '') }}">
    @foreach ($states as $state)
      <option value="{{ $state->value }}" @if (old('state_id', isset($address) ? $address->state_id : '') == $state->value) ) selected @endif>
        {!! $state->name !!}
      </option>
    @endforeach
  </select>
</div>
<div class="form-group  @if ($errors->has('city_id')) has-error @endif">
  <label for="city_id" class="control-label">Municipio</label>
  <div id="dynamic-cities">
    @include('partials._address-cities')
  </div>
</div>

@push('scripts')
  <script src="{{ asset('js/shared/address.js') }}"></script>
@endpush
