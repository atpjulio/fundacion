<select name="companion_eps_service_id" id="companion_eps_service_id" class="form-control"
style="{{ $errors->has('companion_eps_service_id') ? 'border: 1px solid red !important' : '' }}">
	<option value=0>Seleccione</option>
	@foreach($companionServices as $key => $value)
		<option value="{{ $key }}">{{ $value }}</option>
	@endforeach
</select>
