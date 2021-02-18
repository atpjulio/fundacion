<div class="col-md-6">
  <div class="card">
    <div class="card-block">
      <div class="form-group @if ($errors->has('month')) has-error @endif">
        <label for="month" class="control-label">Seleccione mes</label>
        {!! Form::select('month', config('constants.months'), old('month', intval(date('m'))), ['class' =>
        'form-control', 'id' => 'month']) !!}
      </div>
      <div class="form-group @if ($errors->has('year')) has-error @endif">
        <label for="year" class="control-label">Seleccione año</label>
        {!! Form::selectYear('year', 2015, intval(date('Y')) + 5, old('year', intval(date('Y'))), ['class' =>
        'form-control', 'id' => 'year']) !!}
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
