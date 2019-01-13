<div class="modal-header">
    <h4 class="modal-title">
        <i class="fas fa-cogs"></i>
        Servicios para Autorización: {{ $authorization->code }}
    </h4>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
<div class="modal-body">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="text-danger" id="modal-error"></div>
            <div class="text-success" id="modal-success"></div>
            <br>
            <h5>IMPORTANTE:</h5>
            <p>- Con actualizar los días es suficiente. El sistema internamente calculará el nuevo monto de acuerdo al valor parametrizado</p>
            {!! Form::open(['route' => 'authorization.services.update', 'method' => 'POST', 'id' => 'formService']) !!}
            <div class="table-responsive">
                <table class="table table-hover table-bordered" id="service_table">
                    <thead>
                    <tr>
                        <th>Servicio</th>
                        <th>Días</th>
                        <th>Total</th>
                    </tr>
                    </thead>
                    <tbody>
                        @foreach ($authorization->services as $key => $service)                            
                        <tr>
                            <td>
                                <input type="text" id="service_codes" name="service_codes{{ $key }}" class="form-control" placeholder="Número de autorización" value="{{ $service->service->code }}" readonly/>
                            </td>
                            <td>
                                <input type="number" id="service_days" name="service_days{{ $key }}" class="form-control serviceDays" placeholder="Total de días" min="0" value="{{ $service->days }}" />
                                <input type="hidden" name="service_price{{ $key }}" class="form-control" placeholder="" min="0" value="{{ $service->price }}" />
                            </td>
                            <td>
                                <input type="number" id="service_totals" name="service_totals{{ $key }}" class="form-control" placeholder="Valor total" min="0" value="{{ $service->days * $service->price }}" />
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="text-center">
                <a  href="javascript:validateAuthorizationServices('/authorization-services-update', '#formService', '{{ $authorization->invoice_id }}')"
                    class="btn btn-oval btn-warning">
                    Actualizar
                </a>
            </div>
            {{ Form::hidden('services_quantity', count($authorization->services)) }}
            {{ Form::hidden('authorization_id', $authorization->id) }}
            {{ Form::hidden('invoice_id', $authorization->invoice_id) }}
            {!! Form::close() !!}
        </div>
    </div>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-oval btn-secondary" data-dismiss="modal">Cerrar</button>
</div>
