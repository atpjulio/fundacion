$(document).ready(function() {
    $('#state').on('change', function (e) {
        fillCities($('#state').val());
    });
    $("#pricesTable").on('click','.addRow', function() {
      $("#pricesTable").append(
      '<tr>' +
      '<td><input type="number" name="prices[]" value="0" min="1" class="form-control"></td>' +
      '<td><input type="text" name="names[]" value="" class="form-control" placeholder="Ejemplo: Precio Barranquilla"></td>' +
      '<td><a href="javascript:void(0);" class="removeRow btn btn-oval btn-danger">Quitar</a></td>' +
      '</tr>'
    );
    });
    $("#pricesTable").on('click','.removeRow', function() {
        $(this).parent().parent().remove();
    });
});
