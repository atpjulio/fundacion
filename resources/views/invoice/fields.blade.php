<div class="col-12">
    <div class="card">
        <div class="card-block">
            <div class="card-title-block">
                <h3 class="title"> Autorización para esta factura</h3>
            </div>
            <div class="col-12">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered table-condensed table-hover" id="myTable">
                        <thead>
                        <th>Código</th>
                        <th>EPS</th>
                        <th>Usuario</th>
                        <th>Desde</th>
                        <th>Hasta</th>
                        <th>Total Días</th>
                        <th>Opciones</th>
                        </thead>
                        <tbody>
                        @foreach($authorizations as $key => $authorization)
                            <tr>
                                <td>{!! $authorization->codec !!}</td>
                                <td>{!! $authorization->eps->alias ? $authorization->eps->alias : $authorization->eps->name !!}</td>
                                <td>{!! $authorization->patient->full_name !!}</td>
                                <td>{!! \Carbon\Carbon::parse($authorization->date_from)->format("d/m/Y") !!}</td>
                                <td>{!! \Carbon\Carbon::parse($authorization->date_to)->format("d/m/Y") !!}</td>
                                <td>{!! $authorization->days !!}</td>
                                <td>
                                    {!! Form::button('Seleccionar', ['class' => 'btn btn-oval btn-success', 'id' => 'button'.$key ]) !!}
                                    {!! Form::hidden('daily_price', $authorization->daily_price, ['id' => 'daily_price']) !!}
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<div id="beginning" class="col-md-6">
    <div class="card">
        <div class="card-block">
            @if (isset($invoice))
                <div class="form-group @if($errors->has('number')) has-error @endif">
                    {!! Form::label('number', 'Número de factura', ['class' => 'control-label']) !!}
                    {!! Form::number('number', $invoice->number, ['class' => 'form-control underlined', 'placeholder' => 'Número de factura', 'min' => 1, $lastNumber > 0 ? 'readonly' : '']) !!}
                </div>
            @else
                <div class="form-group @if($errors->has('number')) has-error @endif">
                    {!! Form::label('number', 'Número de factura', ['class' => 'control-label']) !!}
                    {!! Form::number('number', ($lastNumber > 0) ? ($lastNumber + 1) : 1, ['class' => 'form-control underlined', 'placeholder' => 'Número de factura', 'min' => 1, $lastNumber > 0 ? 'readonly' : '']) !!}
                </div>
            @endif
            <div class="form-group  @if($errors->has('created_at')) has-error @endif">
                {!! Form::label('created_at', 'Fecha de la factura', ['class' => 'control-label']) !!}
                {!! Form::date('created_at', old('created_at', isset($invoice) ? $invoice->created_at : \Carbon\Carbon::now()), ['class' => 'form-control underlined', 'placeholder' => 'dd/mm/aaaa', isset($show) ? 'readonly' : '']) !!}
            </div>
            <div class="form-group @if($errors->has('company_id')) has-error @endif">
                {!! Form::label('company_id', 'Compañía a la que pertenece la factura', ['class' => 'control-label']) !!}
                {!! Form::select('company_id', $companies, old('company_id', isset($invoice) ? $invoice->company_id : ''), ['class' => 'form-control'   ]) !!}
            </div>
        </div>
    </div>
</div>
<div class="col-md-6">
    <div class="card">
        <div class="card-block">
            <div class="form-group @if($errors->has('authorization_code')) has-error @endif">
                <div class="float-left">
                    {!! Form::label('authorization_code', 'Autorización', ['class' => 'control-label']) !!}
                </div>
                <div class="float-right">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="{{ old('multiple', isset($invoice) ? $invoice->multiple : '0') }}" id="multiple" name="multiple" @if(old('multiple') == "1" or (isset($invoice) and $invoice->multiple)) checked @endif>
                    <label class="form-check-label" for="multiple">
                        ¿Varias autorizaciones?
                    </label>
                    </div>                            
                </div>
                {!! Form::text('authorization_code', old('authorization_code', (isset($invoice) and !$invoice->multiple) ? $invoice->authorization_code : ''), ['class' => 'form-control underlined', 'readonly', 'id' => 'authorization_code']) !!}
            </div>
            <div class="form-group @if($errors->has('total_days')) has-error @endif">
                {!! Form::label('total_days', 'Total de días', ['class' => 'control-label']) !!}
                {!! Form::number('total_days', old('total_days', (isset($invoice) and !$invoice->multiple) ? $invoice->days : ''), ['class' => 'form-control underlined', 'placeholder' => 'Total de días', 'min' => '0', 'id' => 'total_days']) !!}
            </div>
            <div class="form-group @if($errors->has('total')) has-error @endif">
                {!! Form::label('total', 'Monto', ['class' => 'control-label']) !!}
                {!! Form::number('total', old('total', (isset($invoice) and !$invoice->multiple) ? $invoice->total : ''), ['class' => 'form-control underlined', 'placeholder' => 'Valor total de la factura', 'min' => '0', 'id' => 'total']) !!}
            </div>
        </div>
    </div>
</div>
<div class="col-md-12" style="@if(old('multiple') == "1" or (isset($invoice) and $invoice->multiple)) display: block; @else display: none; @endif" id="multiple_card">
    <div class="card">
        <div class="card-block">
            <div class="form-group">
                <div class="table-responsive">
                    <table class="table table-hover table-bordered" id="multiple_table">
                        <thead>
                        <tr>
                            <th>Autorización</th>
                            <th style="width: 160px;">Días</th>
                            <th>Total</th>
                            <th style="min-width: 60px;">Acciones</th>
                        </tr>
                        </thead>
                        <tbody>
                        @if (!isset($invoice))
                            @if (empty(old('multiple_codes')))
                                <tr>
                                    <td>
                                        <input type="text" id="multiple_codes" name="multiple_codes[]" class="form-control" placeholder="Número de autorización" value="" readonly />
                                    </td>
                                    <td>
                                        <input type="number" id="multiple_days" name="multiple_days[]" class="form-control multipleDays" placeholder="Total de días" min="0"/>
                                    </td>
                                    <td>
                                        <input type="number" id="multiple_totals" name="multiple_totals[]" class="form-control" placeholder="Valor total" min="0"/>
                                    </td>
                                    <td>
                                        <a href="javascript:void(0);" class="addRow btn btn-oval btn-success">Añadir</a>
                                    </td>
                                </tr>
                            @else
                                @foreach(old('multiple_codes') as $k => $val)
                                    <tr>
                                        <td>
                                            <input type="text" id="multiple_codes" name="multiple_codes[]" class="form-control" placeholder="Número de autorización" value="{{ $val }}" readonly />
                                        </td>
                                        <td>
                                            <input type="number" id="multiple_days" name="multiple_days[]" class="form-control multipleDays" placeholder="Total de días" min="0" value="{{ old('multiple_days')[$k] }}" />
                                        </td>
                                        <td>
                                            <input type="number" id="multiple_totals" name="multiple_totals[]" class="form-control" placeholder="Valor total" min="0" value="{{ old('multiple_totals')[$k] }}" />
                                        </td>
                                        <td>
                                            @if ($k > 0)
                                                <a href="javascript:void(0);" class="removeRow btn btn-oval btn-danger">Quitar</a>
                                            @else
                                                <a href="javascript:void(0);" class="addRow btn btn-oval btn-success">Añadir</a>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach                            
                            @endif                                
                        @else
                            @if($invoice->multiple)
                            @foreach(old('multiple_codes', json_decode($invoice->multiple_codes, true)) as $k => $val)
                                <tr>
                                    <td>
                                        <input type="text" id="multiple_codes" name="multiple_codes[]" class="form-control" placeholder="Número de autorización" value="{{ $val }}" readonly/>
                                    </td>
                                    <td>
                                        <input type="number" id="multiple_days" name="multiple_days[]" class="form-control multipleDays" placeholder="Total de días" min="0" value="{{ old('multiple_days', json_decode($invoice->multiple_days, true))[$k] }}" />
                                    </td>
                                    <td>
                                        <input type="number" id="multiple_totals" name="multiple_totals[]" class="form-control" placeholder="Valor total" min="0" value="{{ old('multiple_totals',json_decode($invoice->multiple_totals, true))[$k] }}" />
                                    </td>
                                    <td>
                                        <a href="javascript:void(0);" class="removeRow btn btn-oval btn-danger">Quitar</a>
                                    </td>
                                </tr>
                            @endforeach
                            @endif
                        @endif                        
                        </tbody>
                    </table>                
                </div>
                <div class="alert alert-danger alert-dismissible fade show" role="alert" style="background-color: #ffffff !important; color: #dd4b39 !important; display: none;" id="alertTable">
                    <div id="tableMessage">You should check in on some of those fields below.</div>
                </div>
            </div>            
        </div>
    </div>
</div>