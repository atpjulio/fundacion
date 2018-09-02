<div class="form-group @if($errors->has('nit')) has-error @endif">
    {!! Form::label('nit', 'NIT', ['class' => 'control-label']) !!}
    {!! Form::text('nit', old('nit', isset($company) ? $company->nit : ''), ['class' => 'form-control underlined', 'placeholder' => 'NIT de la compañía']) !!}
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
