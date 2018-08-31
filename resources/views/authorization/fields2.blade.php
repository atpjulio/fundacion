<div class="form-group  @if($errors->has('date_from')) has-error @endif">
    {!! Form::label('date_from', 'Fecha de inicio', ['class' => 'control-label']) !!}
    {!! Form::date('date_from', old('date_from', isset($dateFrom) ? $dateFrom : ''), ['class' => 'form-control underlined', 'placeholder' => 'dd/mm/aaaa', isset($show) ? 'readonly' : '']) !!}
</div>
<div class="form-group  @if($errors->has('date_to')) has-error @endif">
    {!! Form::label('date_to', 'Fecha de finalización', ['class' => 'control-label']) !!}
    {!! Form::date('date_to', old('date_to', isset($dateTo) ? $dateTo : ''), ['class' => 'form-control underlined', 'placeholder' => 'dd/mm/aaaa', isset($show) ? 'readonly' : '']) !!}
</div>
@if (isset($show))
    <div class="form-group  @if($errors->has('companion')) has-error @endif">
        {!! Form::label('companion', '¿Viene con acompañante?', ['class' => 'control-label']) !!}
        {!! Form::text('companion', Request::input('companion') ? 'Si' : 'No', ['class' => 'form-control', isset($show) ? 'readonly' : '', 'id' => 'companion']) !!}
    </div>
@else
    <div class="form-group  @if($errors->has('companion')) has-error @endif">
        {!! Form::label('companion', '¿Viene con acompañante?', ['class' => 'control-label']) !!}
        {!! Form::select('companion', config('constants.noYes'), old('companion', isset($authorization) ? $authorization->companion : ''), ['class' => 'form-control', isset($show) ? 'disabled' : '', 'id' => 'companion']) !!}
    </div>
@endif

@if (isset($show) and Request::input('companion'))
    <div class="form-group @if($errors->first('companionDni.*')) has-error @endif col-md-6">
        {!! Form::label('companion_dni', 'Acompañantes', ['class' => 'control-label']) !!}
        <div class="row">
            <div class="col-12">
                <table class="table table-hover table-bordered" id="companionsTable">
                    <thead>
                    <tr>
                        <th style="min-width: 340px;">Documento</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach(Request::input('companionDni') as $k => $val)
                        <tr>
                            <td>
                                <input type="text" id="companionDni" name="companionDni[]" value="{{ $val }}" class="form-control" placeholder="Número de Documento" readonly/>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@else
<div class="form-group @if($errors->first('companionDni.*')) has-error @endif col-md-6" @if ((isset($authorization) and $authorization->companion) or old('companion')) style="display: block;" @else style="display: none;" @endif id="companionsDiv">
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
                @if(isset($authorization))
                    @foreach(explode(',', $authorization->companion_dni) as $k => $val)
                        <tr>
                            <td>
                                <input type="text" id="companionDni" name="companionDni[]" value="{{ $val }}" class="form-control" placeholder="Número de Documento" />
                            </td>
                            <td>
                                @if($k > 0)
                                    <a href="javascript:void(0);" class="removeRow btn btn-oval btn-danger">Quitar</a>
                                @else
                                    <a href="javascript:void(0);" class="addRow btn btn-oval btn-success">Añadir</a>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                @else
                    @if (empty(old('companionDni')))
                    <tr>
                        <td>
                            <input type="text" id="companionDni" name="companionDni[]" value="{{ old('companionDni[]') }}" class="form-control" placeholder="Número de Documento" />
                        </td>
                        <td>
                            <a href="javascript:void(0);" class="addRow btn btn-oval btn-success">Añadir</a>
                        </td>
                    </tr>
                    @else
                        @foreach(old('companionDni') as $k => $val)
                            <tr>
                                <td>
                                    <input type="text" id="companionDni" name="companionDni[]" value="{{ $val }}" class="form-control" placeholder="Número de Documento" />
                                </td>
                                <td>
                                    @if($k > 0)
                                        <a href="javascript:void(0);" class="removeRow btn btn-oval btn-danger">Quitar</a>
                                    @else
                                        <a href="javascript:void(0);" class="addRow btn btn-oval btn-success">Añadir</a>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    @endif
                @endif
                </tbody>
            </table>
        </div>
    </div>
</div>
@endif
