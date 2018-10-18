<div class="form-group @if($errors->has('doc_type')) has-error @endif">
    {!! Form::label('doc_type', 'Tipo de documento', ['class' => 'control-label']) !!}
    {!! Form::select('doc_type', config('constants.companiesDocumentTypes'), old('doc_type', isset($company) ? $company->doc_type : ''), ['class' => 'form-control']) !!}
</div>
<div class="form-group @if($errors->has('doc')) has-error @endif">
    {!! Form::label('doc', 'Número de documento', ['class' => 'control-label']) !!}
    {!! Form::text('doc', old('doc', isset($company) ? $company->doc : ''), ['class' => 'form-control underlined', 'placeholder' => 'Número de documento de la compañía']) !!}
</div>
<div class="form-group @if($errors->has('name')) has-error @endif">
    {!! Form::label('name', 'Nombre completo', ['class' => 'control-label']) !!}
    {!! Form::text('name', old('name', isset($company) ? $company->name : ''), ['class' => 'form-control underlined', 'placeholder' => 'Nombre completo de la compañía']) !!}
</div>
<div class="form-group @if($errors->has('alias')) has-error @endif">
    {!! Form::label('alias', 'Nombre corto o alias', ['class' => 'control-label']) !!}
    {!! Form::text('alias', old('alias', isset($company) ? $company->alias : ''), ['class' => 'form-control underlined', 'placeholder' => 'Nombre corto de la compañía']) !!}
</div>
<div class="form-group @if($errors->has('billing_resolution')) has-error @endif">
    {!! Form::label('billing_resolution', 'Resolución de factura', ['class' => 'control-label']) !!}
    {!! Form::text('billing_resolution', old('billing_resolution', isset($company) ? $company->billing_resolution : ''), ['class' => 'form-control underlined', 'placeholder' => 'Resolución de factura']) !!}
</div>
