<div class="col-md-6" id="beginning">
  <div class="card">
    <div class="card-block">
      <div class="form-group  @if ($errors->has('created_at')) has-error @endif">
        <label class="control-label" for="created_at">Fecha de la nota</label>
        {!! Form::date('created_at', old('created_at', isset($note) ? $note->created_at : \Carbon\Carbon::now()),
        ['class' => 'form-control underlined', 'placeholder' => 'dd/mm/aaaa', isset($show) ? 'readonly' : '']) !!}
      </div>
      <br>
    </div>
  </div>
</div>
<div class="col-md-6">
  <div class="card">
    <div class="card-block">
      <div class="form-group @if ($errors->has('notes')) has-error @endif">
        <label class="control-label" for="notes">Detalles (opcional)</label>
        <textarea name="notes" class="form-control underlined" placeholder="Detalles" rows="2">
          {{ old('notes', isset($note) ? $note->notes : '') }}
        </textarea>
      </div>
    </div>
  </div>
</div>
<div class="col-md-12">
  <div class="card">
    <div class="card-block">
      <puc-component :errors="{{ $errors }}"
        :note-pucs="'{{ json_encode(old('notePucs', isset($note) ? $codes : [])) }}'"
        :puc-description="'{{ json_encode(old('pucDescription', isset($note) ? $descriptions : [])) }}'"
        :puc-debit="'{{ json_encode(old('pucDebit', isset($note) ? $debits : [])) }}'"
        :puc-credit="'{{ json_encode(old('pucCredit', isset($note) ? $credits : [])) }}'"></puc-component>
    </div>
  </div>
</div>
