<div class="col-md-6" id="beginning">
  <div class="card">
    <div class="card-block">
      <div class="form-group  @if ($errors->has('created_at')) has-error @endif">
				<label for="created_at" class="control-label">Fecha del recibo de pago</label>
        {!! Form::date('created_at', old('created_at', isset($receipt) ? $receipt->created_at : \Carbon\Carbon::now()),
        ['class' => 'form-control underlined', 'placeholder' => 'dd/mm/aaaa']) !!}
      </div>
      <div class="form-group @if ($errors->has('entity_id')) has-error @endif">
				<label for="entity_id" class="control-label">Hemos recibido de</label>
        <select name="entity_id" id="entity_id" class="form-control">
          <option value="0">-- Añadir nuevo --</option>
          @foreach ($entities as $ent)
            <option value="{{ $ent->id }}" @if (isset($receipt) and $receipt->entity_id == $ent->id or old('entity_id') == $ent->id) selected @endif>
              {!! $ent->name !!}
            </option>
          @endforeach
        </select>
      </div>
      <div class="form-group @if ($errors->has('concept')) has-error @endif">
				<label for="concept" class="control-label">Por concepto de</label>
        <textarea name="concept" class="form-control underlined" placeholder="Concepto" rows="4">
          {{ old('concept', isset($receipt) ? $receipt->concept : '') }}
        </textarea>
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
        :note-pucs="'{{ json_encode(old('notePucs', isset($receipt) ? $codes : [])) }}'"
        :puc-description="'{{ json_encode(old('pucDescription', isset($receipt) ? $descriptions : [])) }}'"
        :puc-debit="'{{ json_encode(old('pucDebit', isset($receipt) ? $debits : [])) }}'"
        :puc-credit="'{{ json_encode(old('pucCredit', isset($receipt) ? $credits : [])) }}'"></puc-component>
    </div>
  </div>
</div>
