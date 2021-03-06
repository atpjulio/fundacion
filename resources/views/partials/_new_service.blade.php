<div class="modal-header">
    <h4 class="modal-title">
        <i class="fas fa-cogs"></i>
        Nuevo servicio {!! $eps->alias ? 'para '.$eps->alias : '' !!}
        </h4>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
<div class="modal-body">
    <div class="row justify-content-center">
        <div class="col-md-10">
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
        </div>
    </div>
</div>
<div class="modal-footer"></div>
