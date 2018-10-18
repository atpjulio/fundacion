<div class="form-group  @if($errors->has('date_from')) has-error @endif">
    {!! Form::label('date_from', 'Fecha de inicio', ['class' => 'control-label']) !!}
    {!! Form::date('date_from', old('date_from', isset($dateFrom) ? $dateFrom : ''), ['class' => 'form-control underlined', 'placeholder' => 'dd/mm/aaaa', isset($show) ? 'readonly' : '']) !!}
</div>
<div class="form-group  @if($errors->has('date_to')) has-error @endif">
    {!! Form::label('date_to', 'Fecha de finalización', ['class' => 'control-label']) !!}
    {!! Form::date('date_to', old('date_to', isset($dateTo) ? $dateTo : ''), ['class' => 'form-control underlined', 'placeholder' => 'dd/mm/aaaa', isset($show) ? 'readonly' : '']) !!}
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
