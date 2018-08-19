{!! Form::select('eps_service_id', $services, old('eps_service_id', isset($authorization) ? $authorization->eps_service_id : ''), ['class' => 'form-control']) !!}
