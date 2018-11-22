<select name="multiple_services" class="form-control" @if($errors->has("multiple_services")) style="border: 1px solid red !important;" @endif>
    @foreach($services as $key => $service)
    	<option value="0">Seleccione adicional</option>
        @if (isset($service->id))
            <option value="{{ $service->id }}" @if(old('multiple_services', isset($authorization) ? $authorization->multiple_services : '') == $service->id) selected @endif>
             {!! $service->code.' - '.$service->name !!}
            </option>
        @else
            <option value="{{ $key }}">{!! $service !!}</option>
        @endif
    @endforeach
</select>
