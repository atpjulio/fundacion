<div class="form-group @if ($errors->has('doc_type')) has-error @endif">
  <label for="doc_type" class="control-label">Tipo de documento</label>
  {!! Form::select('doc_type', config('constants.companiesDocumentTypes'), old('doc_type', isset($company) ?
  $company->doc_type : ''), ['class' => 'form-control']) !!}
</div>
<div class="form-group @if ($errors->has('doc')) has-error @endif">
  <label for="doc" class="control-label">Número de documento</label>
  {!! Form::text('doc', old('doc', isset($company) ? $company->doc : ''), ['class' => 'form-control underlined',
  'placeholder' => 'Número de documento de la compañía']) !!}
</div>
<div class="form-group @if ($errors->has('name')) has-error @endif">
  <label for="name" class="control-label">Nombre completo</label>
  {!! Form::text('name', old('name', isset($company) ? $company->name : ''), ['class' => 'form-control underlined',
  'placeholder' => 'Nombre completo de la compañía']) !!}
</div>
<div class="form-group @if ($errors->has('alias')) has-error @endif">
  <label for="alias" class="control-label">Nombre corto o alias</label>
  {!! Form::text('alias', old('alias', isset($company) ? $company->alias : ''), ['class' => 'form-control underlined',
  'placeholder' => 'Nombre corto de la compañía']) !!}
</div>
<div class="form-group @if ($errors->has('billing_resolution')) has-error @endif">
  <label for="billing_resolution" class="control-label">Resolución de factura</label>
  {!! Form::text('billing_resolution', old('billing_resolution', isset($company) ? $company->billing_resolution : ''),
  ['class' => 'form-control underlined', 'placeholder' => 'Resolución de factura']) !!}
</div>
<div class="form-group @if ($errors->has('billing_date')) has-error @endif">
  <label for="billing_date" class="control-label">Fecha de resolución de facturación</label>
  {!! Form::date('billing_date', old('billing_date', isset($company) ? $company->billing_date : ''), ['class' =>
  'form-control underlined', 'placeholder' => 'dd/mm/aaaa']) !!}
</div>
<div class="row">
  <div class="col-md-6">
    <div class="form-group @if ($errors->has('billing_start')) has-error @endif">
      <label for="billing_start" class="control-label">Desde</label>
      {!! Form::number('billing_start', old('billing_start', isset($company) ? $company->billing_start : ''), ['class'
      => 'form-control underlined', 'placeholder' => 'Desde', 'min' => 0]) !!}
    </div>
  </div>
  <div class="col-md-6">
    <div class="form-group @if ($errors->has('billing_end')) has-error @endif">
      <label for="billing_end" class="control-label">Hasta</label>
      {!! Form::number('billing_end', old('billing_end', isset($company) ? $company->billing_end : ''), ['class' =>
      'form-control underlined', 'placeholder' => 'Hasta', 'min' => 0]) !!}
    </div>
  </div>
</div>
<div class="form-group @if ($errors->has('logo')) has-error @endif">
  <label for="logo" class="control-label">Logo (opcional)</label>
  @if (isset($company) and $company->logo)
    <div class="text-center">
      <img src="{{ asset($company->logo) }}" class="img-thumbnail w-50">
    </div>
  @endif
  <div class="custom-file">
    <input type="file" class="custom-file-input" id="customFileLang" lang="es" name="logo">
    <label class="custom-file-label form-control-file" for="customFileLang">Seleccionar archivo</label>
  </div>
</div>
