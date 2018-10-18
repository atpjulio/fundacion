<div class="col-md-6" id="beginning">
    <div class="card">
        <div class="card-block">
			<div class="form-group  @if($errors->has('created_at')) has-error @endif">
			    {!! Form::label('created_at', 'Fecha del comprobante', ['class' => 'control-label']) !!}
			    {!! Form::date('created_at', old('created_at', isset($egress) ? $egress->created_at : \Carbon\Carbon::now()), ['class' => 'form-control underlined', 'placeholder' => 'dd/mm/aaaa']) !!}
			</div>
			<div class="form-group  @if($errors->has('created_at')) has-error @endif">
				{!! Form::label('bank_id', 'Banco de donde sale el dinero') !!}
				{!! Form::select('bank_id', config('constants.banks'), old('bank_id', isset($egress) ? $egress->bank_id : ''), ['class' => 'form-control']) !!}
			</div>
			<div class="form-group  @if($errors->has('created_at')) has-error @endif">
				{!! Form::label('payment_type', 'Forma de pago') !!}
				{!! Form::select('payment_type', config('constants.paymentType'), old('payment_type', isset($egress) ? $egress->payment_type : ''), ['class' => 'form-control']) !!}
			</div>
        </div>
    </div>
</div>
<div class="col-md-6">
    <div class="card">
        <div class="card-block">
			<div class="form-group @if($errors->has('company_id')) has-error @endif" style="margin-bottom: 31px;">
			    {!! Form::label('company_id', 'Compañía a la que pertenece el comprobante', ['class' => 'control-label']) !!}
			    {!! Form::select('company_id', $companies, old('company_id', isset($egress) ? $egress->company_id : ''), ['class' => 'form-control'   ]) !!}
			</div>
			<div class="form-group @if($errors->has('notes')) has-error @endif">
			    {!! Form::label('notes', 'Detalles (opcional)', ['class' => 'control-label']) !!}
			    {!! Form::textarea('notes', old('notes', isset($egress) ? $egress->notes : ''), ['class' => 'form-control underlined', 'placeholder' => 'Detalles', 'rows' => 4]) !!}
			</div>
        </div>
    </div>
</div>
<div class="col-md-12">
    <div class="card">
        <div class="card-block">
			<div class="form-group">
			    {!! Form::label('pucs', 'Listado de códigos PUC', ['class' => 'control-label']) !!}
			    <select name="pucs" class="form-control" id="pucs">
			        <option>Seleccione el código PUC</option>
			        @foreach($pucs as $puc)
			            <option value="{{ $puc->code }}">{!! $puc->code.' - '.$puc->description !!}</option>
			        @endforeach
			    </select>
			</div>

			<div class="form-group @if($errors->first('notePucs.*')) has-error @endif">
			    <div class="row">
			        <div class="col-12">
			            <table class="table table-hover table-bordered">
			                <thead>
			                <tr>
			                    <th style="width: 135px !important;">Código</th>
			                    <th style="">Descripción</th>
			                    <th style="width: 150px !important;">Débitos</th>
			                    <th style="width: 150px !important;">Créditos</th>
			                    <th style="width: 85px !important;">Acción</th>
			                </tr>
			                </thead>
			                <tbody>
			                    <tr>
			                        <td>
			                            <input type="text" id="puc_code" name="puc_code" class="form-control" placeholder="Código PUC" />
			                        </td>
			                        <td>
			                            <input type="text" id="puc_description" name="puc_description" placeholder="Descripción" class="form-control">
			                        </td>
			                        <td>
			                            <input type="text" id="puc_debit" name="puc_debit" placeholder="Débitos" class="form-control">
			                        </td>
			                        <td>
			                            <input type="text" id="puc_credit" name="puc_credit" placeholder="Créditos" class="form-control">
			                        </td>
			                        <td>
			                            <a href="javascript:void(0);" class="addRow btn btn-oval btn-info">Añadir</a>
			                        </td>
			                    </tr>
			                </tbody>
			            </table>
			        </div>
			    </div>
			</div>


			<div class="form-group @if($errors->first('notePucs.*')) has-error @endif">
			    {!! Form::label('notePucs', 'Códigos PUC del comprobante', ['class' => 'control-label']) !!}
			    <div class="row">
			        <div class="col-12">
			            <table class="table table-hover table-bordered" id="pucsTable">
			                <thead>
			                <tr>
			                    <th style="width: 135px !important;">Código</th>
			                    <th style="">Descripción</th>
			                    <th style="width: 150px !important;">Débitos</th>
			                    <th style="width: 150px !important;">Créditos</th>
			                    <th style="width: 85px !important;">Acción</th>
			                </tr>
			                </thead>
			                <tbody>
			                @if(isset($egress) and count($egress->pucs) > 0 and empty(old('notePucs')))
			                    @foreach($egress->pucs as $notePuc)
			                        <tr>
			                            <td>
			                                <input type="text" id="notePucs" name="notePucs[]" value="{{ $notePuc->code }}" class="form-control" placeholder="Código PUC" />
			                            </td>
			                            <td>
			                                <input type="text" name="pucDescription[]" placeholder="Descripción" class="form-control" value="{{ $notePuc->description }}">
			                            </td>
			                            <td>
			                                <input type="text" name="pucDebit[]" placeholder="Débitos" class="form-control" value="{{ $notePuc->type ? 0 : $notePuc->amount }}">
			                            </td>
			                            <td>
			                                <input type="text" name="pucCredit[]" placeholder="Créditos" class="form-control" value="{{ $notePuc->type ? $notePuc->amount : 0 }}">
			                            </td>
			                            <td>
			                                <a href="javascript:void(0);" class="removeRow btn btn-oval btn-danger">Quitar</a>
			                            </td>
			                        </tr>
			                    @endforeach
			                @endif
			                @if (!empty(old('notePucs')))
			                    @foreach(old('notePucs') as $k => $val)
			                        <tr>
			                            <td class="@if($errors->first('notePucs.*')) has-error @endif">
			                                <input type="text" id="notePucs" name="notePucs[]" value="{{ $val }}" class="form-control" placeholder="Código PUC" />
			                            </td>
			                            <td class="@if($errors->first('pucDescription.*')) has-error @endif">
			                                <input type="text" name="pucDescription[]" placeholder="Descripción" class="form-control" value="{{ old('pucDescription')[$k] }}">
			                            </td>
			                            <td>
			                                <input type="text" name="pucDebit[]" placeholder="Débitos" class="form-control" value="{{ old('pucDebit')[$k] }}">
			                            </td>
			                            <td>
			                                <input type="text" name="pucCredit[]" placeholder="Créditos" class="form-control" value="{{ old('pucCredit')[$k] }}">
			                            </td>
			                            <td>
			                                <a href="javascript:void(0);" class="removeRow btn btn-oval btn-danger">Quitar</a>
			                            </td>
			                        </tr>
			                    @endforeach
			                @endif
			                </tbody>
			            </table>
			        </div>
			    </div>
			</div>
        </div>
    </div>
</div>
