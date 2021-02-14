<div class="col-md-6" id="beginning">
  <div class="card">
    <div class="card-block">
      <div class="form-group  @if ($errors->has('created_at')) has-error @endif">
        <label for="created_at" class="control-label">Fecha del egreso</label>
        {!! Form::date('created_at', old('created_at', isset($egress) ? $egress->created_at : \Carbon\Carbon::now()),
        ['class' => 'form-control underlined', 'placeholder' => 'dd/mm/aaaa']) !!}
      </div>
      <div class="form-group @if ($errors->has('entity_id')) has-error @endif">
        <label for="entity_id" class="control-label">Pagado a</label>
        <select name="entity_id" id="entity_id" class="form-control">
          <option value="0">-- Añadir nuevo --</option>
          @foreach ($entities as $ent)
            <option value="{{ $ent->id }}" @if (isset($egress) and $egress->entity_id == $ent->id or old('entity_id') == $ent->id) selected @endif>
              {!! $ent->name !!}
            </option>
          @endforeach
        </select>
      </div>
      <div class="form-group @if ($errors->has('concept')) has-error @endif">
        <label for="concept" class="control-label">Por concepto de</label>
        {!! Form::textarea('concept', old('concept', isset($egress) ? $egress->concept : ''), ['class' => 'form-control
        underlined', 'placeholder' => 'Concepto', 'rows' => 4]) !!}
      </div>
    </div>
  </div>
</div>
<div class="col-md-6">
  <div class="card">
    <div class="card-block" id="dynamic-entity-fields">
      @include('partials._entity_fields')
    </div>
  </div>
</div>
<div class="col-md-12">
  <div class="card">
    <div class="card-block">
      <puc-component :errors="{{ $errors }}"
        :note-pucs="'{{ json_encode(old('notePucs', isset($egress) ? $codes : [])) }}'"
        :puc-description="'{{ json_encode(old('pucDescription', isset($egress) ? $descriptions : [])) }}'"
        :puc-debit="'{{ json_encode(old('pucDebit', isset($egress) ? $debits : [])) }}'"
        :puc-credit="'{{ json_encode(old('pucCredit', isset($egress) ? $credits : [])) }}'"></puc-component>
    </div>
  </div>
</div>
