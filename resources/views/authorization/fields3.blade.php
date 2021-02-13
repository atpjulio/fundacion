@if (isset($show) and Request::input('companion'))
    <div class="form-group @if($errors->first('companionDni.*')) has-error @endif col-md-6">
        <div class="row">
            <div class="col-12">
                <table class="table table-hover table-bordered" id="companionsTable">
                    <thead>
                    <tr>
                        <th style="min-width: 340px;">Documento</th>
                        <th style="min-width: 340px;">Servicio para acompañante</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach(Request::input('companionDni') as $k => $val)
                        <tr>
                            <td>
                                <input type="text" id="companionDni" name="companionDni[]" value="{{ $val }}" class="form-control" placeholder="Número de Documento" readonly/>
                            </td>
                            <td>
                                <input type="text" id="companionService" name="companionService[]" class="form-control" placeholder="Servicio para el acompañante" readonly value="{{ Request::input('companionService')[$k] }}"/>
                                <input type="hidden" name="companionServiceId[]" id="companionServiceId" value="{{ Request::input('companionServiceId')[$k] }}">                                                                          
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@else
@if (!isset($show))
<div class="form-group">
    {!! Form::label('companion_services', 'Seleccione el servicio que aplica al acompañante', ['class' => 'control-label']) !!}
    <div class="row">
        @php
            $companionServices = \App\EpsService::getServices(old('eps_id') ?: $initialEpsId)->pluck('name', 'id');
            if (count($companionServices) < 1) {
                $companionServices = [
                    "0" => 'Sin servicios registrados'
                ];
            }
        @endphp
        <div class="col-12">
            <div id="dynamic-companion-services">
                @include('partials._companion_services')
            </div>
        </div>
    </div>
</div>
@endif
<div class="form-group">
    <div class="table-responsive">
        <table class="table table-hover table-bordered">
            <thead>
            <tr>
                <th style="width: 160px;">Documento</th>
                <th>Servicio para acompañante</th>
                <th style="min-width: 60px;">Acciones</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td>
                    <input type="text" id="companion_document" name="companion_document" class="form-control" placeholder="Número de Documento" />
                </td>
                <td>
                    <input type="text" id="companion_service" name="companion_service" class="form-control" placeholder="Servicio para el acompañante" readonly />
                    <input type="hidden" name="companion_service_id" id="companion_service_id" >                    
                </td>
                <td>
                    <a href="javascript:void(0);" class="addRow btn btn-oval btn-success">Añadir</a>
                </td>
            </tr>
            </tbody>
        </table>                
    </div>
    <div class="alert alert-danger alert-dismissible fade show" role="alert" style="background-color: #ffffff !important; color: #dd4b39 !important; display: none;" id="alertTable">
        <div id="tableMessage">You should check in on some of those fields below.</div>
    </div>
</div>
<div class="form-group">
    <label for="companions" class="control-label">Acompañante(s) incluído(s) en la autorización</label>
    <div class="table-responsive @if($errors->first('companionDni.*')) has-error @endif ">

        <table class="table table-hover table-bordered" id="companionsTable">
            <thead>
            <tr>
                <th style="width: 160px;">Documento</th>
                <th>Servicio para acompañante</th>
                <th style="min-width: 60px;">Acciones</th>
            </tr>
            </thead>
            <tbody>
            @if(isset($authorization) and $authorization->companion_dni)
                @php
                    $servicesForCompanions = explode(',', $authorization->companion_eps_service_id);                    
                @endphp

                @foreach(explode(',', $authorization->companion_dni) as $k => $val)
                    @php
                        $sc = \App\EpsService::find($servicesForCompanions[$k]);
                    @endphp
                    <tr>
                        <td>
                            <input type="text" id="companionDni" name="companionDni[]" value="{{ $val }}" class="form-control" placeholder="Número de Documento" />
                        </td>
                        <td>
                            <input type="text" id="companionService" name="companionService[]" class="form-control" placeholder="Servicio para el acompañante" readonly value="{{ $sc->name }}" />
                            <input type="hidden" name="companionServiceId[]" id="companionServiceId" value="{{ $sc->id }}">
                        </td>
                        <td>
                            <a href="javascript:void(0);" class="removeRow btn btn-oval btn-danger">Quitar</a>
                        </td>
                    </tr>
                @endforeach
            @else
                @if (!empty(old('companionDni')))
                    @foreach(old('companionDni') as $k => $val)
                        <tr>
                            <td>
                                <input type="text" id="companionDni" name="companionDni[]" value="{{ $val }}" class="form-control" placeholder="Número de Documento" />
                            </td>
                            <td>
                                <input type="text" id="companionService" name="companionService[]" class="form-control" placeholder="Servicio para el acompañante" readonly />
                                <input type="hidden" name="companionServiceId[]" id="companionServiceId" >                    
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
</div>
@endif
