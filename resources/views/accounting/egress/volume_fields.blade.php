<div class="col-md-6" >
    <div class="card">
        <div class="card-block">
            <div class="form-group @if($errors->has('month')) has-error @endif">
                {!! Form::label('month', 'Seleccione mes', ['class' => 'control-label']) !!}
                {!! Form::select('month', config('constants.months'), old('month', intval(date("m"))), ['class' => 'form-control', 'id' => 'month']) !!}
            </div>
            <div class="form-group @if($errors->has('year')) has-error @endif">
                {!! Form::label('year', 'Seleccione año', ['class' => 'control-label']) !!}
                {!! Form::selectYear('year', 2015, intval(date("Y")) + 5, old('year', intval(date("Y"))), ['class' => 'form-control', 'id' => 'year']) !!}
            </div>
            <br>
        </div>
    </div>
</div>
<div class="col-md-6">
    <div class="card">
        <div class="card-block">
            <div class="form-group  @if($errors->has('created_at')) has-error @endif">
                {!! Form::label('created_at', 'Fecha de generación', ['class' => 'control-label']) !!}
                {!! Form::date('created_at', old('created_at', isset($rip) ? $rip->created_at : \Carbon\Carbon::now()), ['class' => 'form-control underlined', 'placeholder' => 'dd/mm/aaaa', isset($show) ? 'readonly' : '']) !!}
            </div>
            @include('partials._egress_amount')
        </div>
    </div>
</div>

