<div class="col-12">
  <div class="card">
    <div class="card-block">
      <div class="card-title-block">
        <h3 class="title"> Autorización para esta factura</h3>
      </div>
      <div class="col-12">
        <div class="table-responsive">
          <table class="table table-striped table-bordered table-condensed table-hover" id="myTable">
            <thead>
              <th>Código</th>
              <th>EPS</th>
              <th>Usuario</th>
              <th>Desde</th>
              <th>Hasta</th>
              <th>Total Días</th>
              <th>Opciones</th>
            </thead>
            <tbody>
              @foreach ($authorizations as $key => $authorization)
                <tr>
                  <td>{!! $authorization->codec !!}</td>
                  <td>{!! $authorization->eps->alias ? $authorization->eps->alias : $authorization->eps->name !!}</td>
                  <td>{!! $authorization->patient->full_name !!}</td>
                  <td>{!! \Carbon\Carbon::parse($authorization->date_from)->format('d/m/Y') !!}</td>
                  <td>{!! \Carbon\Carbon::parse($authorization->date_to)->format('d/m/Y') !!}</td>
                  <td>{!! $authorization->days !!}</td>
                  <td>
                    <button type="button" class="btn btn-oval btn-success"
                      id="button{{ $key }}">Seleccionar</button>
                    <input type="hidden" name="daily_price"
                      value="{{ isset($authorization->price) ? $authorization->price->daily_price : $authorization->daily_price }}"
                      id="daily_price">
                  </td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>
<div id="beginning" class="col-md-12">
  <div class="card">
    <div class="card-block">
      <div class="row">
        <div class="col-md-3">
          @if (isset($invoice))
            <div class="form-group @if ($errors->has('number')) has-error @endif">
              <label for="number" class="control-label">Número de factura</label>
              <input type="number" name="number" class="form-control underlined" value="{{ $invoice->number }}"
                placeholder="Número de factura" min="1">
            </div>
          @else
            <div class="form-group @if ($errors->has('number')) has-error @endif">
              <label for="number" class="control-label">Número de factura</label>
              <input type="number" name="number" class="form-control underlined"
                value="{{ $lastNumber > 0 ? $lastNumber + 1 : 1 }}" placeholder="Número de factura" min="1">
            </div>
          @endif
        </div>
        <div class="col-md-3">
          <div class="form-group  @if ($errors->has('created_at')) has-error @endif">
            <label for="created_at" class="control-label">Fecha de la factura</label>
            <input type="date" name="created_at" placeholder="dd/mm/aaaa" class="form-control underlined" value="{{ old('created_at', isset($invoice) ? $invoice->created_at : now()) }}" @isset($show) readonly @endisset>
          </div>
        </div>
        <div class="col-md-6">
          <div class="form-group @if ($errors->has('company_id')) has-error @endif">
            <label for="company_id" class="control-label">Compañía a la que pertenece la factura</label>
            {!! Form::select('company_id', $companies, old('company_id', isset($invoice) ? $invoice->company_id : ''),
            ['class' => 'form-control']) !!}
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@if (isset($invoice))
  <div class="col-md-12">
    <div class="card">
      <div class="card-block">
        <div class="row">
          <div class="col-md-3">
            <div class="form-group @if ($errors->has('total_days')) has-error @endif">
              <label for="total_days" class="control-label">Total de días</label>
              <input type="number" name="total_days" id="total_days" class="form-control underlined"
                value="{{ old('total_days', (isset($invoice) and !$invoice->multiple) ? $invoice->days : '') }}"
                placeholder="Total de días" min="0">
            </div>
          </div>
          <div class="col-md-3">
            <div class="form-group @if ($errors->has('total')) has-error @endif">
              <label for="total" class="control-label">Monto</label>
              <input type="number" name="total" id="total" min="0" class="form-control underlined"
                value="{{ old('total', (isset($invoice) and !$invoice->multiple) ? $invoice->total : '') }}"
                placeholder="Valor total de la factura">
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group @if ($errors->has('authorization_code')) has-error @endif">
              <div class="float-left">
                <label for="authorization_code" class="control-label">Autorización</label>
              </div>
              <div class="float-right">
                <div class="form-check">
                  <input class="form-check-input" type="checkbox"
                    value="{{ old('multiple', isset($invoice) ? $invoice->multiple : '0') }}" id="multiple"
                    name="multiple" @if (old('multiple') == '1' or isset($invoice) and $invoice->multiple) checked @endif>
                  <label class="form-check-label" for="multiple">
                    ¿Varias autorizaciones?
                  </label>
                </div>
              </div>
              <input type="text" name="authorization_code" id="authorization_code" class="form-control underlined"
                readonly
                value="{{ old('authorization_code', (isset($invoice) and !$invoice->multiple) ? $invoice->authorization_code : '') }}">
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
@else
  <input type="hidden" name="multiple" value="1">
@endif
<div class="col-md-12" style="@if (old('multiple')=='1' or isset($invoice) and $invoice->
multiple) display: block; @else display: none; @endif" id="multiple_card">
  @include('partials._invoice_multiple_table')
</div>
