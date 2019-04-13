<select name="city" id="city" class="form-control">
    @foreach($cities as $code => $name)
    <option value="{{ sprintf("%03d", $code) }}" @if(isset($patient) and $patient->city == $code) selected @endif>
        {!! $code.' - '.$name !!}
    </option>
    @endforeach
</select>

