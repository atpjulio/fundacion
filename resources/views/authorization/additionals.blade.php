<div class="card">
    <div class="card-block">
        <div class="card-title-block">
            <h3 class="title"> Adicionales </h3>
        </div>
        <div class="form-group  @if($errors->has('companion')) has-error @endif">
            {!! Form::label('companion', '¿Viene con acompañante(s)?', ['class' => 'control-label']) !!}
            {!! Form::select('companion', config('constants.noYes'), old('companion', isset($authorization) ? $authorization->companion : ''), ['class' => 'form-control', isset($show) ? 'disabled' : '', 'id' => 'companion']) !!}
        </div>
        <div id="companionsDiv"
            @if (old('companion') or (isset($authorization) and $authorization->companion))
                style="display: block;"
            @else
                style="display: none;"
            @endif>
            @include('authorization.companion')
        </div>
        <div class="form-group">
          {!! Form::label('multiple_services', 'Servicios adicionales', ['class' => 'control-label']) !!}
          <div class="row">
              <div class="col-md-10">
                <div id="dynamic-multiple-services">
                    @include('partials._services_multiple')
                </div>
              </div>
              <div class="col-md-2 text-right">
                  <a id="addRowService"
                    href="javascript:void(0)"
                    class="btn btn-oval btn-secondary addRowService">
                    Añadir
                  </a>
              </div>
          </div>
        </div>
        <div class="form-group" id="multipleServicesDiv"
            @if (old('multiple_services') || (isset($authorization) and $authorization->services))
                style="display: block;"
            @else
                style="display: none;"
            @endif>
          <div class="table-responsive">
            <table class="table table-striped table-bordered table-condensed table-hover" id="multipleServicesTable">
              <thead>
                <tr>
                  <th style="width: 150px;">Código</th>
                  <th>Nombre</th>
                  <th style="width: 100px;">Días</th>
                  <th style="width: 100px;">Opciones</th>
                </tr>
              </thead>
              <tbody>
                @if (old('service_code'))
                    @foreach (old('service_code') as $key => $value)
                      <tr>
                          <td><input type="text" name="service_code[]" value="{{ $value }}" class="form-control" readonly></td>
                          <td><input type="text" name="service_description[]" value="{{ old('service_description')[$key] }}" class="form-control" readonly></td>
                          <td><input type="number" name="service_days[]" value="{{ old('service_days')[$key] }}" class="form-control" min="1"></td>
                          <td><a href="javascript:void(0);" class="removeRowService btn btn-oval btn-danger">Quitar</a></td>
                      </tr>
                    @endforeach
                @elseif(isset($authorization) and $authorization->services and count($authorization->services) > 0)
                    @foreach ($authorization->services as $key => $authorizationService)
                        @if ($key > 0)
                            <tr>
                                <td><input type="text" name="service_code[]" value="{{ $authorizationService->service->code }}" class="form-control" readonly></td>
                                <td><input type="text" name="service_description[]" value="{{ $authorizationService->service->name }}" class="form-control" readonly></td>
                                <td><input type="number" name="service_days[]" value="{{ $authorizationService->days }}" class="form-control" min="1"></td>
                                <td><a href="javascript:void(0);" class="removeRowService btn btn-oval btn-danger">Quitar</a></td>
                            </tr>
                        @endif
                    @endforeach
                @elseif(isset($authorization))
                    @php
                        $oldVersionService = \App\EpsService::find($authorization->multiple_services);
                    @endphp
                    @if ($oldVersionService)
                    <tr>
                        <td><input type="text" name="service_code[]" value="{{ $oldVersionService->code }}" class="form-control" readonly></td>
                        <td><input type="text" name="service_description[]" value="{{ $oldVersionService->name }}" class="form-control" readonly></td>
                        <td><input type="number" name="service_days[]" value="{{ $authorization->days }}" class="form-control" min="1"></td>
                        <td><a href="javascript:void(0);" class="removeRowService btn btn-oval btn-danger">Quitar</a></td>
                    </tr>
                    @endif
                @endif
              </tbody>
            </table>
          </div>
        </div>
    </div>
</div>
