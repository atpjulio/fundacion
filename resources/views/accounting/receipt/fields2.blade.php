<div class="form-group @if($errors->has('notes')) has-error @endif">
    {!! Form::label('notes', 'Detalles (opcional)', ['class' => 'control-label']) !!}
    {!! Form::textarea('notes', old('notes', isset($receipt) ? $receipt->notes : ''), ['class' => 'form-control underlined', 'placeholder' => 'Detalles', 'rows' => 5]) !!}
</div>
