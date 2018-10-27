<div class="form-group  @if($errors->has('date_from')) has-error @endif">
    {!! Form::label('date_from', 'Fecha de inicio', ['class' => 'control-label']) !!}
    {!! Form::date('date_from', old('date_from', isset($dateFrom) ? $dateFrom : ''), ['class' => 'form-control underlined', 'placeholder' => 'dd/mm/aaaa', isset($show) ? 'readonly' : '']) !!}
</div>
<div class="form-group  @if($errors->has('total_days')) has-error @endif">
    {!! Form::label('total_days', 'Total de días', ['class' => 'control-label']) !!}
    {!! Form::number('total_days', old('total_days', isset($authorization) ? $authorization->days : 1), ['class' => 'form-control underlined', 'placeholder' => '0', isset($show) ? 'readonly' : '', 'min' => 1]) !!}
</div>
@if (isset($show))
    <div class="form-group  @if($errors->has('companion')) has-error @endif">
        {!! Form::label('companion', '¿Viene con acompañante?', ['class' => 'control-label']) !!}
        {!! Form::text('companion', Request::input('companion') ? 'Si' : 'No', ['class' => 'form-control', isset($show) ? 'readonly' : '', 'id' => 'companion']) !!}
    </div>
@else
    <div class="form-group  @if($errors->has('companion')) has-error @endif">
        {!! Form::label('companion', '¿Viene con acompañante?', ['class' => 'control-label']) !!}
        {!! Form::select('companion', config('constants.noYes'), old('companion', isset($authorization) ? $authorization->companion : ''), ['class' => 'form-control', isset($show) ? 'disabled' : '', 'id' => 'companion']) !!}
    </div>
@endif
