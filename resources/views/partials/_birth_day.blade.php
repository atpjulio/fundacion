{!! Form::selectRange('birth_day', 1, $finalDay, isset($patient) ? substr($patient->birth_date, 8, 10) : 1, ['class' => 'form-control', 'id' => 'birth_day']) !!}
