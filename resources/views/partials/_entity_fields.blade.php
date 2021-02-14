<div class="form-group @if ($errors->has('name')) has-error @endif">
  <label for="name" class="control-label">Nombre de la entidad o persona</label>
  {!! Form::text('name', old('name', isset($entity) ? $entity->name : ''), ['class' => 'form-control underlined', 'id'
  => 'name']) !!}
</div>
<div class="form-group @if ($errors->has('doc')) has-error @endif">
  <label for="doc" class="control-label">NIT / CC</label>
  {!! Form::text('doc', old('doc', isset($entity) ? $entity->doc : ''), ['class' => 'form-control underlined', 'id' =>
  'doc']) !!}
</div>
<div class="form-group @if ($errors->has('address')) has-error @endif">
  <label for="address" class="control-label">Dirección</label>
  {!! Form::text('address', old('address', isset($entity) ? $entity->address : ''), [
  'class' => 'form-control
  underlined',
  'id' => 'address',
  ]) !!}
</div>
<div class="form-group @if ($errors->has('phone')) has-error @endif">
  <label for="phone" class="control-label">Teléfono</label>
  {!! Form::text('phone', old('phone', isset($entity) ? $entity->phone : ''), ['class' => 'form-control underlined',
  'id' => 'phone']) !!}
</div>
