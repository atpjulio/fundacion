<div class="col-md-6">
  <div class="card">
    <div class="card-block">
      <div class="form-group @if ($errors->has('month')) has-error @endif">
        <label for="month" class="control-label">Seleccione mes</label>
        <select name="month" id="month" class="form-control">
          @foreach (config('constants.months') as $value => $option)
            <option value="{{ $value }}" @if (old('month', intval(date('m'))) == $value) selected @endif>
              {{ $option }}
              </option>
          @endforeach
        </select>
      </div>
      <div class="form-group @if ($errors->has('year')) has-error @endif">
        <label for="year" class="control-label">Seleccione año</label>
        <select name="year" id="year" class="form-control">
          @for ($i = intval(date('Y')); $i <= intval(date('Y')) + 5; $i++)
            <option value="{{ $i }}">{{ $i }}</option>
          @endfor
        </select>
      </div>
      <br>
    </div>
  </div>
</div>
<div class="col-md-6">
  <div class="card">
    <div class="card-block">
      <div class="form-group  @if ($errors->has('created_at')) has-error @endif">
        <label for="created_at" class="control-label">Fecha de generación</label>
        <input type="date" name="created_at" placeholder="dd/mm/aaaa" class="form-control underlined"
          value="{{ old('created_at', isset($rip) ? $rip->created_at : now()) }}" @isset($show) readonly @endisset>
      </div>
      @include('partials._egress_amount')
    </div>
  </div>
</div>
