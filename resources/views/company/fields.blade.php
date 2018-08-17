<div class="form-group">
    {!! Form::label('picture', 'Imagen', ['class' => 'control-label']) !!}
    {!! Form::file('picture', ['class' => 'form-control underlined', 'placeholder' => 'Imagen del producto']) !!}
</div><div class="form-group">
    {!! Form::label('name', 'Nombre', ['class' => 'control-label']) !!}
    {!! Form::text('name', '', ['class' => 'form-control underlined', 'placeholder' => 'Nombre del producto']) !!}
</div>
<div class="form-group">
    {!! Form::label('quantity', 'Cantidad', ['class' => 'control-label']) !!}
    {!! Form::number('quantity', '', ['class' => 'form-control underlined', 'placeholder' => 'Cantidad inicial disponible', 'min' => '0']) !!}
</div>
<div class="form-group">
    {!! Form::label('description', 'Descripción', ['class' => 'control-label']) !!}
    {!! Form::textarea('description', '', ['class' => 'form-control underlined', 'placeholder' => 'Descripción del producto', 'rows' => '3']) !!}
</div>
