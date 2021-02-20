<select name="birth_day" id="birth_day" class="form-control">
  @for ($i = 1; $i <= $finalDay; $i++)
    <option @if (isset($patient) ? substr($patient->birth_date, 8, 10) : 1) selected @endif value="{{ $i }}">{{ $i }}</option>
  @endfor
</select>