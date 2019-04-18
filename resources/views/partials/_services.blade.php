<select name="eps_service_id" id="eps_service_id" class="form-control" @if($errors->has("eps_service_id")) style="border: 1px solid red !important;" @endif>
    @foreach($services as $key => $service)
        @if (isset($service->id))
            <option value="{{ $service->id }}" @if(old('eps_service_id', isset($authorization) ? $authorization->eps_service_id : '') == $service->id) selected @endif>
             {!! $service->code.' - '.$service->name.' - $'.$service->price !!}
            </option>
        @else
            <option value="{{ $key }}">{!! $service !!}</option>
        @endif
    @endforeach
</select>
