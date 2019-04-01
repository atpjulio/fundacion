<div class="col-md-6" id="beginning">
    <div class="card">
        <div class="card-block">
			{{-- 
			<div class="form-group @if($errors->has('invoice_number')) has-error @endif">
			    {!! Form::label('invoice_number', 'Número de factura seleccionada', ['class' => 'control-label']) !!}
			    {!! Form::text('invoice_number', old('invoice_number', isset($note) ? $note->invoice->number : ''), ['class' => 'form-control underlined', 'readonly', 'id' => 'invoice_number']) !!}
			</div>
			<div class="form-group @if($errors->has('amount')) has-error @endif">
			    {!! Form::label('amount', 'Monto de la nota', ['class' => 'control-label']) !!}
			    {!! Form::number('amount', old('amount', isset($note) ? $note->amount : 0), ['class' => 'form-control underlined', 'placeholder' => 'Monto de la nota', 'min' => '0']) !!}
			</div>
			 --}}
			<div class="form-group  @if($errors->has('created_at')) has-error @endif">
			    {!! Form::label('created_at', 'Fecha de la nota', ['class' => 'control-label']) !!}
			    {!! Form::date('created_at', old('created_at', isset($note) ? $note->created_at : \Carbon\Carbon::now()), ['class' => 'form-control underlined', 'placeholder' => 'dd/mm/aaaa', isset($show) ? 'readonly' : '']) !!}
			</div>
			<br>
        </div>
    </div>
</div>
<div class="col-md-6">
    <div class="card">
        <div class="card-block">
			<div class="form-group @if($errors->has('notes')) has-error @endif">
			    {!! Form::label('notes', 'Detalles (opcional)', ['class' => 'control-label']) !!}
			    {!! Form::textarea('notes', old('notes', isset($note) ? $note->notes : ''), ['class' => 'form-control underlined', 'placeholder' => 'Detalles', 'rows' => 2]) !!}
			</div>
        </div>
    </div>
</div>
<div class="col-md-12">
    <div class="card">
        <div class="card-block">
			<puc-component :errors="{{ $errors }}" 
				:note-pucs="'{{ json_encode(old('notePucs', isset($note) ? $codes: [])) }}'"
				:puc-description="'{{ json_encode(old('pucDescription', isset($note) ? $descriptions: [])) }}'"
				:puc-debit="'{{ json_encode(old('pucDebit', isset($note) ? $debits : [])) }}'"
				:puc-credit="'{{ json_encode(old('pucCredit', isset($note) ? $credits : [])) }}'"
			></puc-component>
        </div>
    </div>
</div>
