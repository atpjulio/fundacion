<div class="form-group @if($errors->has('name')) has-error @endif">
    {!! Form::label('name', 'Nombre de la entidad o persona', ['class' => 'control-label']) !!}
    {!! Form::text('name', old('name', isset($entity) ? $entity->name : ''), ['class' => 'form-control underlined', 'id' => 'name']) !!}
</div>
<div class="form-group @if($errors->has('doc')) has-error @endif">
    {!! Form::label('doc', 'NIT / CC', ['class' => 'control-label']) !!}
    {!! Form::text('doc', old('doc', isset($entity) ? $entity->doc : ''), ['class' => 'form-control underlined', 'id' => 'doc']) !!}
</div>
<div class="form-group @if($errors->has('address')) has-error @endif">
    {!! Form::label('address', 'Dirección', ['class' => 'control-label']) !!}
    {!! Form::text('address', old('address', isset($entity) ? $entity->address : ''), ['class' => 'form-control underlined', 'id' => 'address']) !!}
</div>
<div class="form-group @if($errors->has('phone')) has-error @endif">
    {!! Form::label('phone', 'Teléfono', ['class' => 'control-label']) !!}
    {!! Form::text('phone', old('phone', isset($entity) ? $entity->phone : ''), ['class' => 'form-control underlined', 'id' => 'phone']) !!}
</div>
