<div class="form-group @if($errors->first('notePucs.*')) has-error @endif">
    {!! Form::label('notePucs', 'Códigos PUC de la nota de contabilidad', ['class' => 'control-label']) !!}
    <div class="row">
        <div class="col-12">
            <table class="table table-hover table-bordered" id="pucsTable">
                <thead>
                <tr>
                    <th style="width: 135px !important;">Código</th>
                    <th style="">Cuentas</th>
                    <th style="width: 135px !important;">Débitos</th>
                    <th style="width: 135px !important;">Créditos</th>
                    <th style="width: 85px !important;">Acción</th>
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
                                <input type="text" name="puc_description" placeholder="Cuentas">
                            </td>
                            <td>
                                <input type="text" name="puc_debit" placeholder="Débitos">
                            </td>
                            <td>
                                <input type="text" name="puc_credit" placeholder="Créditos">
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
                    @if (empty(old('notePucs')))
                        <tr>
                            <td>
                                <input type="text" id="notePucs" name="notePucs[]" value="{{ old('notePucs[]') }}" class="form-control" placeholder="Código PUC" />
                            </td>
                            <td>
                                <input type="text" name="puc_description" placeholder="Cuentas" class="form-control">
                            </td>
                            <td>
                                <input type="text" name="puc_debit" placeholder="Débitos" class="form-control">
                            </td>
                            <td>
                                <input type="text" name="puc_credit" placeholder="Créditos" class="form-control">
                            </td>
                            <td>
                                <a href="javascript:void(0);" class="addRow btn btn-oval btn-info">Añadir</a>
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
                                        <a href="javascript:void(0);" class="addRow btn btn-oval btn-info">Añadir</a>
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
