$(document).ready(function() {
    var days = 0;
    $('#total').val($('#selected_price').val() * $('#total_days').val());
    $('#myTable').on('click','.btn-success', function() {
        days = parseInt($(this).parent().parent().find('td')[5].outerText);
        $('#multiple').prop('checked', true);
        $('#multiple').val("1");
        $('#multiple_card').css('display', 'block');
        $('#multiple_card').addClass('animated fadeIn');
        $('#authorization_code').val('');
        $('#total_days').val('');
        $('#total').val('');

        if ($('#multiple').is(":checked")) {
            if ($("#multiple_table tr:nth-child(1)").find('td input')[0].value.length > 0) {
                $("#multiple_table").append(
                    '<tr><td><input type="text" id="multiple_codes" name="multiple_codes[]" value="' + $(this).parent().parent().find('td').first()[0].outerText.trim() + '" class="form-control" placeholder="Número de autorización" readonly />'
                    + '</td><td><input type="number" id="multiple_days" name="multiple_days[]" value="' + days + '" class="form-control multipleDays" placeholder="Total de días" min="0"/>'
                    + '</td><td><input type="number" id="multiple_totals" name="multiple_totals[]" value="' + ($(this).parent().find('input')[0].value * days) + '" class="form-control" placeholder="Valor total" min="0"/>'
                    + '</td><td><a href="javascript:void(0);" class="removeRow btn btn-oval btn-danger">Quitar</a></td></tr>'
                );
            } else {
                $("#multiple_table tr:last").remove();
                $("#multiple_table").append(
                    '<tr><td><input type="text" id="multiple_codes" name="multiple_codes[]" value="' + $(this).parent().parent().find('td').first()[0].outerText.trim() + '" class="form-control" placeholder="Número de autorización" readonly />'
                    + '</td><td><input type="number" id="multiple_days" name="multiple_days[]" value="' + days + '" class="form-control multipleDays" placeholder="Total de días" min="0"/>'
                    + '</td><td><input type="number" id="multiple_totals" name="multiple_totals[]" value="' + ($(this).parent().find('input')[0].value * days) + '" class="form-control" placeholder="Valor total" min="0"/>'
                    + '</td><td><a href="javascript:void(0);" class="addRow btn btn-oval btn-success">Añadir</a></td></tr>'
                );
            }

            $('#alertTable').css('display', 'none');
            $('#tableMessage').html('');
            $('#selected_price').val($(this).parent().find('input')[0].value);

            document.querySelector('#beginning').scrollIntoView({
                behavior: 'smooth',
                block: 'start'
            });
            history.pushState(null, null, '#beginning');
            e.preventDefault();
        } else {
            $('#total_days').val(days);
            $('#selected_price').val($(this).parent().find('input')[0].value);
            $('#total').val($('#selected_price').val() * $('#total_days').val());
            //$('#total').val($('#selected_price').val() * $('#total_days').val() + parseInt($('#total').val()));
            $('#authorization_code').val($(this).parent().parent().find('td').first()[0].outerText.trim());
            //$('#authorization_code').val($(this).parent().parent().find('td').first()[0].outerText.trim() + "," + $('#authorization_code').val());

            document.querySelector('#beginning').scrollIntoView({
                behavior: 'smooth',
                block: 'start'
            });
            history.pushState(null, null, '#beginning');
            e.preventDefault();
        }
    });
    $('#total_days').on('change', function (e) {
        $('#total').val($('#selected_price').val() * $('#total_days').val());
    });
    $('#multiple_table').on('change', '.multipleDays', function (e) {
        $(this).parent().parent().find('td input')[2].value = e.target.value * $('#selected_price').val();
    });
    $('#multiple').on('change', function (e) {
        if ($('#multiple').is(":checked")) {
            $('#multiple').val("1");
            $('#multiple_card').css('display', 'block');
            $('#multiple_card').addClass('animated fadeIn');
            $('#authorization_code').val('');
            $('#total_days').val('');
            $('#total').val('');
        } else {
            $('#multiple_card').css('display', 'none');
            $('#multiple').val("0");
        }
    });
    $("#multiple_table").on('click','.addRow', function() {
        if ($('#multiple_codes').val().length > 0 && $('#multiple_days').val() > 0 && $('#multiple_totals').val() > 0) {
            $("#multiple_table").append(
                '<tr><td><input type="text" id="multiple_codes" name="multiple_codes[]" class="form-control" placeholder="Número de autorización" value=""/>'
                + '</td><td><input type="number" id="multiple_days" name="multiple_days[]" class="form-control multipleDays" placeholder="Total de días" min="0" value=""/>'
                + '</td><td><input type="number" id="multiple_totals" name="multiple_totals[]" class="form-control" placeholder="Valor total" min="0" value=""/>'
                + '</td><td><a href="javascript:void(0);" class="removeRow btn btn-oval btn-danger">Quitar</a></td></tr>'
            );
            $('#alertTable').css('display', 'none');
            $('#tableMessage').html('');
        } else {
            if ($('#multiple_codes').val().length == 0) {
                $('#tableMessage').html('Número de autorización inválido');
                $('#alertTable').css('display', 'block');
            } else if ($('#multiple_days').val().length == 0) {
                $('#tableMessage').html('Número de días inválido');
                $('#alertTable').css('display', 'block');
            } else if ($('#multiple_totals').val().length == 0) {
                $('#tableMessage').html('Monto de factura inválido');
                $('#alertTable').css('display', 'block');
            }
        }

    });
    $("#multiple_table").on('click','.removeRow', function() {
        $(this).parent().parent().remove();
    });

} );
