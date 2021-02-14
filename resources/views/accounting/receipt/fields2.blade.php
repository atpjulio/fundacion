<div class="form-group @if ($errors->has('notes')) has-error @endif">
  <label for="notes" class="control-label">Detalles (opcional)</label>
  <textarea name="notes" placeholder="Detalles" rows="5" class="form-control underlined">
    {{ old('notes', isset($receipt) ? $receipt->notes : '') }}
  </textarea>
</div>
