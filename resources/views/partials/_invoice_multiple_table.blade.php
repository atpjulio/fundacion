<div class="card">
    <div class="card-block">
        <div class="form-group">
            <div class="table-responsive">
                <table class="table table-hover table-bordered" id="multiple_table">
                    <thead>
                    <tr>
                        <th>Autorización</th>
                        <th style="width: 100px;">Días</th>
                        <th>Total</th>
                        <th style="min-width: 160px;">Acciones</th>
                    </tr>
                    </thead>
                    <tbody>
                    @if (!isset($invoice) or !$invoice->multiple)
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
                                    <input type="hidden" id="multiple_price" name="multiple_price[]" class="form-control" placeholder="" min="0" value="" />
                                </td>
                                <td>
                                    <a href="javascript:void(0);" class="addRow btn btn-oval btn-success">Añadir</a>
                                    &nbsp;<a href="javascript:void(0);" class="servicesDetail btn btn-oval btn-secondary">Servicios</a>
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
                                        <input type="hidden" id="multiple_price" name="multiple_price[]" class="form-control" placeholder="" min="0" value="{{ old('multiple_totals')[$k] / old('multiple_days')[$k] }}" />
                                    </td>
                                    <td>
                                        @if ($k > 0)
                                            <a href="javascript:void(0);" class="removeRow btn btn-oval btn-danger">Quitar</a>
                                        @else
                                            <a href="javascript:void(0);" class="addRow btn btn-oval btn-success">Añadir</a>
                                        @endif
                                        &nbsp;<a href="javascript:void(0);" class="servicesDetail btn btn-oval btn-secondary">Servicios</a>
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                    @else
                        @if($invoice->multiple)
                        @foreach (old('multiple_codes', json_decode($invoice->multiple_codes, true)) as $k => $val)
                            @php
                                $currentAuthorization = \App\Authorization::findByCode($val);
                            @endphp

                            <tr>
                                <td>
                                    <input type="text" id="multiple_codes" name="multiple_codes[]" class="form-control" placeholder="Número de autorización" value="{{ $val }}" readonly/>
                                </td>
                                <td>
                                    <input type="number" id="multiple_days" name="multiple_days[]" class="form-control multipleDays" placeholder="Total de días" min="0" value="{{ old('multiple_days', json_decode($invoice->multiple_days, true))[$k] }}" />
                                </td>
                                <td>
                                    @if ($currentAuthorization->price)
                                        <input type="number" id="multiple_totals" name="multiple_totals[]" class="form-control" placeholder="Valor total" min="0" value="{{ old('multiple_totals', json_decode($invoice->multiple_days, true)[$k] * $currentAuthorization->price->daily_price) }}" />                                    
                                        <input type="hidden" id="multiple_price" name="multiple_price[]" class="form-control" placeholder="" min="0" value="{{ $currentAuthorization->price->daily_price }}" />
                                    @else                                    
                                        <input type="number" id="multiple_totals" name="multiple_totals[]" class="form-control" placeholder="Valor total" min="0" value="{{ old('multiple_totals', json_decode($invoice->multiple_days, true)[$k] * json_decode($invoice->multiple_totals, true)[$k]) }}" />
                                        <input type="hidden" id="multiple_price" name="multiple_price[]" class="form-control" placeholder="" min="0" value="{{ json_decode($invoice->multiple_totals, true)[$k] }}" />
                                    @endif
                                </td>
                                <td>
                                    <a href="javascript:void(0);" class="removeRow btn btn-oval btn-danger">Quitar</a>
                                    &nbsp;<a href="javascript:void(0);" class="servicesDetail btn btn-oval btn-secondary">Servicios</a>
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
