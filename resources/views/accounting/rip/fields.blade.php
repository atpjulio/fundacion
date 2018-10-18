<div class="form-group  @if($errors->has('company_id')) has-error @endif">
    {!! Form::label('company_id', 'Seleccione la compañía', ['class' => 'control-label']) !!}
    {!! Form::select('company_id', $companies, old('company_id', isset($rip) ? $rip->company_id : ''), ['class' => 'form-control']) !!}
</div>

@if (isset($show))
    <div class="form-group  @if($errors->has('eps_name')) has-error @endif">
        {!! Form::label('eps_name', 'Nombre de EPS', ['class' => 'control-label']) !!}
        {!! Form::text('eps_name', old('eps_name', isset($rip) ? $rip->name : ''), ['class' => 'form-control underlined', 'placeholder' => 'Nombre de EPS', 'readonly']) !!}
        {!! Form::hidden('eps_id', $rip->id) !!}
    </div>
@else
    <div class="form-group  @if($errors->has('eps_id')) has-error @endif">
        {!! Form::label('eps_id', 'Seleccione EPS', ['class' => 'control-label']) !!}
        {!! Form::select('eps_id', $epss, old('eps_id', isset($rip) ? $rip->eps_id : ''), ['class' => 'form-control', 'id' => 'eps_id']) !!}
    </div>
@endif

<div class="row">
    <div class="col-6">
        <div class="form-group  @if($errors->has('initial_date')) has-error @endif">
            {!! Form::label('initial_date', 'Fecha inicial', ['class' => 'control-label']) !!}
            {!! Form::date('initial_date', old('initial_date', isset($rip) ? $rip->initial_date : \Carbon\Carbon::now()), ['class' => 'form-control underlined', 'id' => 'initial_date', 'placeholder' => 'dd/mm/aaaa', isset($show) ? 'readonly' : '']) !!}
        </div>
    </div>
    <div class="col-6">
        <div class="form-group  @if($errors->has('final_date')) has-error @endif">
            {!! Form::label('final_date', 'Fecha final', ['class' => 'control-label']) !!}
            {!! Form::date('final_date', old('final_date', isset($rip) ? $rip->final_date : \Carbon\Carbon::now()), ['class' => 'form-control underlined', 'id' => 'final_date', 'placeholder' => 'dd/mm/aaaa', isset($show) ? 'readonly' : '']) !!}
        </div>
    </div>
</div>
