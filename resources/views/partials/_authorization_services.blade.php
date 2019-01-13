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
            <div class="table-responsive">
                <table class="table table-hover table-bordered" id="multiple_table">
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
                                <input type="text" id="multiple_codes" name="multiple_codes[]" class="form-control" placeholder="Número de autorización" value="{{ $service->service->code }}" readonly/>
                            </td>
                            <td>
                                <input type="number" id="multiple_days" name="multiple_days[]" class="form-control multipleDays" placeholder="Total de días" min="0" value="{{ $service->days }}" />
                            </td>
                            <td>
                                <input type="number" id="multiple_totals" name="multiple_totals[]" class="form-control" placeholder="Valor total" min="0" value="{{ $service->days * $service->price }}" />
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
                {{--  
            {!! Form::open(['route' => 'eps.services.new', 'method' => 'POST', 'id' => 'formService']) !!}
                <div class="card">
                    <div class="card-block">
                        <div class="text-danger" id="modal-error"></div>
                        <div class="text-success" id="modal-success"></div>
                        @include('eps.services.fields')
                    </div>
                </div>
                <div class="text-center">
                    {!! Form::hidden('eps_id', $eps->id) !!}
                    <a  href="javascript:validateFormService('/eps-services/new', '#formService', '{{ $eps->id }}')"
                        class="btn btn-oval btn-primary">
                        Guardar
                    </a>
                </div>
            {!! Form::close() !!}
            --}}
        </div>
    </div>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-oval btn-secondary" data-dismiss="modal">Cerrar</button>
</div>

