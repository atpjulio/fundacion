{!! Form::select('city', $cities, old('city', isset($address) ? $address->city : ''), ['class' => 'form-control']) !!}
