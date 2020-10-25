@if (count($invoices) > 0)
<div class="pb-3 row">
    <div class="col-md-3">
        <div class="form-group  @if($errors->has('export_method')) has-error @endif">
            {!! Form::label('export_method', 'Opciones de exportación', ['class' => 'control-label']) !!}
            {!! Form::select('export_method', config('constants.exportMethods.options'), null, ['class' => 'form-control']) !!}
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group  @if($errors->has('export_date')) has-error @endif">
            {!! Form::label('export_date', 'Fecha de exportación', ['class' => 'control-label']) !!}
            {!! Form::date('export_date', old('export_date', now()), ['class' => 'form-control underlined']) !!}
        </div>
    </div>
    <div class="col-md-6 pt-2">
        <br>
        <button type="submit" class="btn btn-oval btn-success" name="export" value="export">
            <i class="fas fa-file-export"></i>
            Exportar
        </button>
    </div>
</div>
@endif
<div class="table-responsive">
    <table class="table table-striped table-bordered table-condensed table-hover" id="">
        <thead>
            <th># Factura</th>
            <th>Paciente</th>
            <th>Autorización</th>
            <th>Monto</th>
            <th>Total</th>
        </thead>
        <tbody>
            @if (count($invoices) < 1)
            <tr>
                <td colspan="5" class="text-center">
                    Sin resultados que mostrar
                </td>
            </tr>
            @else
            @foreach ($invoices as $invoice)
                @php
                $routes = '';
                if (!$invoice->multiple) {
                $routes = '<a href="'. route('authorization.code', $invoice->authorization_code). '"
                    target="_blank">'.$invoice->authorization_code.'</a>';
                } else {
                $codesArray = json_decode($invoice->multiple_codes, true);
                foreach ($codesArray as $key => $code) {
                $routes .= '<a href="'. route('authorization.code', $code). '" target="_blank">'.$code.'</a>';
                if (count($codesArray) > $key - 1) {
                $routes .= '<br>';
                }
                }
                }
                $patient = $invoice->getPatient();
                @endphp
                <tr>
                    <td>
                        <a href="{{ route('invoice.edit', $invoice->id) }}" target="_blank" rel="noopener noreferrer">
                            {!! $invoice->format_number !!}
                        </a>
                        {!! ' - ' . optional($invoice->eps)->alias !!}
                    </td>                    
                    <td>
                        @if ($patient)
                            <a href="{{ route('patient.edit', $patient->id) }}" target="_blank"
                                rel="noopener noreferrer">
                                {!! $patient->full_name !!}
                            </a>
                        @else
                            --
                        @endif
                    </td>
                    <td>{!! $routes !!}</td>
                    <td>$ {!! !$invoice->multiple ? number_format($invoice->total, 2, ',', '.') : join('<br>$ ',
                        $invoice->multiple_totals_formated) !!}</td>
                    <td>
                        $ {!! number_format($invoice->calculateTotal(), 2, ',', '.') !!}
                        {{-- @if (in_array($invoice->id, $except))
                        <a href="{{ route('invoice.edit', $invoice->id) }}" class="btn btn-oval btn-info btn-sm">
                            Agregar
                        </a>
                        @else
                        <a href="javascript:showModal('invoice/delete/{{ $invoice->id }}')"
                            class="btn btn-oval btn-warning btn-sm">
                            Quitar
                        </a>
                        @endif --}}
                    </td>
                </tr>
            @endforeach
            @endif
        </tbody>
    </table>
</div>
@if (count($invoices) > 0)
    <div class="float-right">
        {{ $invoices->appends([
            'initial_number' => request()->get('initial_number'), 
            'final_number' => request()->get('final_number'), 
            'initial_date' => request()->get('initial_date'), 
            'final_date' => request()->get('final_date'), 
            'selection' => $oldSelection, 
            'except' => request()->get('except'), 
            'eps_id' => request()->get('eps_id'), 
            'company_id' => request()->get('company_id'),
        ])->links() }}
    </div>
@endif
