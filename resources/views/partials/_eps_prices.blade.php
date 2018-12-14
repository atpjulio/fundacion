<div class="card">
    <div class="card-block">
        <div class="card-title-block">
            <h3 class="title"> Tarifa(s) Diaria(s) </h3>
        </div>
        <div class="table-responsive">
          <table class="table table-hover table-bordered" id="pricesTable">
            <thead>
              <tr>
                <th>Precio</th>
                <th>Nombre</th>
                <th>Opciones</th>
              </tr>
            </thead>
            <tbody>
              @if (!old('prices') and !isset($eps) or (isset($eps) and count($eps->price) == 0))
              <tr>
                <td>
                  <input type="number" name="prices[]" value="{{ isset($eps) && $eps->daily_price > 0 ? $eps->daily_price : '' }}" min="1" class="form-control">
                </td>
                <td>
                  <input type="text" name="names[]" value="" class="form-control" placeholder="Ejemplo: Precio Barranquilla">
                </td>
                <td>
                  <a href="javascript:void(0);" class="addRow btn btn-oval btn-success">Añadir</a>
                </td>
              </tr>
              @elseif(old('prices'))
                @foreach (old('prices') as $key => $price)
                  <tr>
                    <td>
                      <input type="number" name="prices[]" value="{{ $price }}" min="1" class="form-control">
                    </td>
                    <td>
                      <input type="text" name="names[]" value="{{ old('names')[$key] }}" class="form-control" placeholder="Ejemplo: Precio Barranquilla">
                    </td>
                    <td>
                      <a href="javascript:void(0);" class="addRow btn btn-oval btn-success">Añadir</a>
                    </td>
                  </tr>
                @endforeach
              @else
                @foreach ($eps->price as $key => $epsPrice)
                  <tr>
                    <td>
                      <input type="number" name="prices[]" value="{{ $epsPrice->daily_price }}" min="1" class="form-control">
                    </td>
                    <td>
                      <input type="text" name="names[]" value="{{ $epsPrice->name }}" class="form-control" placeholder="Ejemplo: Precio Barranquilla">
                    </td>
                    <td>
                      <a href="javascript:void(0);" class="addRow btn btn-oval btn-success">Añadir</a>
                    </td>
                  </tr>
                @endforeach
              @endif
            </tbody>
          </table>
        </div>
    </div>
</div>
