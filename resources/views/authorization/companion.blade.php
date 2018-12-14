<div class="table-responsive">
  <table class="table table-striped table-bordered table-condensed table-hover" id="companionsTable">
    <thead>
      <tr>
        <th>Documento</th>
        <th>Nombre completo</th>
        <th>Número de contacto</th>
        <th style="width: 100px;">Opciones</th>
      </tr>
    </thead>
    <tbody>
    @if(!old('companion_dni') && !isset($authorization))
        <tr>
            <td>
              <input type="text" name="companion_dni[]" value="" maxlength="20" class="form-control">
            </td>
            <td>
              <input type="text" name="companion_name[]" value="" maxlength="50" class="form-control">
            </td>
            <td>
              <input type="text" name="companion_phone[]" value="" maxlength="15" class="form-control">
            </td>
            <td>
              <a href="javascript:void(0);" class="addRow btn btn-oval btn-success">Añadir</a>
            </td>
        </tr>
    @elseif(old('companion_dni'))
        @foreach (old('companion_dni') as $key => $value)
            <tr>
                <td>
                  <input type="text" name="companion_dni[]" value="{{ $value }}" maxlength="20" class="form-control">
                </td>
                <td>
                  <input type="text" name="companion_name[]" value="{{ old('companion_name')[$key] }}" maxlength="50" class="form-control">
                </td>
                <td>
                  <input type="text" name="companion_phone[]" value="{{ old('companion_phone')[$key] }}" maxlength="15" class="form-control">
                </td>
                <td>
                  @if ($key > 0)
                      <a href="javascript:void(0);" class="removeRow btn btn-oval btn-danger">Quitar</a>
                  @else
                      <a href="javascript:void(0);" class="addRow btn btn-oval btn-success">Añadir</a>
                  @endif
                </td>
            </tr>
        @endforeach
    @elseif(isset($authorization) and $authorization->companions and count($authorization->companions) > 0)
        @foreach ($authorization->companions as $key => $companion)
            <tr>
                <td>
                  <input type="text" name="companion_dni[]" value="{{ $companion->dni }}" maxlength="20" class="form-control">
                </td>
                <td>
                  <input type="text" name="companion_name[]" value="{{ $companion->name }}" maxlength="50" class="form-control">
                </td>
                <td>
                  <input type="text" name="companion_phone[]" value="{{ $companion->phone }}" maxlength="15" class="form-control">
                </td>
                <td>
                  @if ($key > 0)
                      <a href="javascript:void(0);" class="removeRow btn btn-oval btn-danger">Quitar</a>
                  @else
                      <a href="javascript:void(0);" class="addRow btn btn-oval btn-success">Añadir</a>
                  @endif
                </td>
            </tr>
        @endforeach
    @else
        <tr>
            <td>
              <input type="text" name="companion_dni[]" value="{{ $authorization->companion_dni }}" maxlength="20" class="form-control">
            </td>
            <td>
              <input type="text" name="companion_name[]" value="{{ $authorization->companion_name }}" maxlength="50" class="form-control">
            </td>
            <td>
              <input type="text" name="companion_phone[]" value="{{ $authorization->companion_phone }}" maxlength="15" class="form-control">
            </td>
            <td>
                <a href="javascript:void(0);" class="addRow btn btn-oval btn-success">Añadir</a>
            </td>
        </tr>
    @endif
    </tbody>
  </table>
</div>
