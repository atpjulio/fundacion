<select name="city_id" id="city_id" class="form-control underlined">
  @foreach ($cities as $city)
    <option value="{{ $city->value }}" 
      @if (old('city_id', $oldCityId ?? '') == $city->value) selected @endif
    >
      {!! $city->name !!}
    </option>
  @endforeach
</select>
