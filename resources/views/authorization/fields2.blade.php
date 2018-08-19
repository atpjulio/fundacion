<div class="form-group  @if($errors->has('date_from')) has-error @endif">
    {!! Form::label('date_from', 'Fecha de inicio', ['class' => 'control-label']) !!}
    {!! Form::date('date_from', old('date_from', isset($dateFrom) ? $dateFrom : ''), ['class' => 'form-control underlined', 'placeholder' => 'dd/mm/aaaa', isset($show) ? 'readonly' : '']) !!}
</div>
<div class="form-group  @if($errors->has('date_to')) has-error @endif">
    {!! Form::label('date_to', 'Fecha de finalización', ['class' => 'control-label']) !!}
    {!! Form::date('date_to', old('date_to', isset($dateTo) ? $dateTo : ''), ['class' => 'form-control underlined', 'placeholder' => 'dd/mm/aaaa', isset($show) ? 'readonly' : '']) !!}
</div>
<div class="form-group  @if($errors->has('companion')) has-error @endif">
    {!! Form::label('companion', '¿Viene con acompañante?', ['class' => 'control-label']) !!}
    {!! Form::select('companion', config('constants.noYes'), old('companion', isset($notes) ? $notes : ''), ['class' => 'form-control', isset($show) ? 'disabled' : '', 'id' => 'companion']) !!}
</div>
<div class="form-group @if($errors->first('companionDni.*')) has-error @endif col-md-6" style="display: none;" id="companionsDiv">
    {!! Form::label('companion_dni', 'Acompañantes', ['class' => 'control-label']) !!}
    <div class="row">
        <div class="col-12">
            <table class="table table-hover table-bordered" id="companionsTable">
                <thead>
                <tr>
                    <th style="min-width: 340px;">Documento</th>
                    <th style="min-width: 60px;">Acciones</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td>
                        <input type="text" id="companionDni" name="companionDni[]" value="{{ old('companionDni[]') }}" class="form-control" placeholder="Número de Documento" />
                    </td>
                    <td>
                        <a href="javascript:void(0);" class="addRow btn btn-oval btn-success">Añadir</a>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
