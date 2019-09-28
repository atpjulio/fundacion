$(document).ready(function() {
    var code        = '';
    var description = '';
    $('#searching').on('change', function (e) {
        fillFilteredEpsPatients($('#searching').val());
    });

    $('#code').on('change keyup', function (e) {
        if ($('#code').val().length > 4) {
            checkAuthorization($('#code').val());
        }
    });

    $('#dynamic-services').on('change', '#eps_service_id', function (e) {
        var myUrl = '/get-service/' + $('#eps_service_id').val();

        $.ajax({
            method: "GET",
            headers: { "X-CSRF-TOKEN" : $("#_tokenBase").val() },
            cache: false,
            url: myUrl,
    
            success: function(response) {
                $('#daily_price').val(response);
            },
            error: function(errors){
                console.log(errors);
            }
        });
    });

    $('#companion').on('change', function (e) {
        if ($('#companion').val() == 1) {
            $('#companionsDiv').css('display', 'block');
            $('#companionsDiv').addClass('animated fadeIn');
        } else {
            $('#companionsDiv').css('display', 'none');
        }
    });
    $('#epsSelect').on('change', function (e) {
        $('#serviceLink').attr("href", "/eps-services/" + $('#epsSelect').val() + "/create-from-authorization");
        fillServices($('#epsSelect').val());
        fillMultipleServices($('#epsSelect').val());
        fillDailyPrices($('#epsSelect').val());
        if (code != '' || description != '') {
            var totalRows = $("#multipleServicesTable").find('tr').length;
            if (totalRows > 1) {
                $("#multipleServicesTable").find('tr').each(function(index, element) {
                    if (index > 0) {
                        element.remove();
                    }
                });
            }
        }
        // fillPatients($('#epsSelect').val());
        // fillCompanionServices($('#epsSelect').val());
    });
    $('#companion_eps_service_id').on('change', function(e) {
        if ($(this).val() > 0) {
            $('#companion_service').val($(this).children('option').filter(":selected").text());
            $('#companion_service_id').val($(this).val());
            $('#alertTable').css('display', 'none');
            $('#tableMessage').html('');
        } else {
            $('#companion_service').val('');
            $('#companion_service_id').val('');
            $('#tableMessage').html('Por favor seleccione un servicio válido');
            $('#alertTable').css('display', 'block');
        }
    });
    $("#companionsTable").on('click','.addRow', function() {
        $("#companionsTable").append(
          '<tr>' +
          '<td><input type="text" name="companion_name[]" value="" maxlength="50" class="form-control"></td>' +
          '<td><input type="text" name="companion_dni[]" value="" maxlength="20" class="form-control"></td>' +
            '<td><input type="text" name="companion_phone[]" value="" maxlength="15" class="form-control"></td>' +
            '<td><a href="javascript:void(0);" class="removeRow btn btn-oval btn-danger">Quitar</a></td>' +
          '</tr>'
        );
    });
    $("#companionsTable").on('click','.removeRow', function() {
        $(this).parent().parent().remove();
    });
    $("#companionsTable").on('click','.removeRow', function() {
        $(this).parent().parent().remove();
    });
    $(".addRowService").on('click', function() {
        code = $('#multiple_services option:selected').text().split(" - ")[0].trim();
        description = $('#multiple_services option:selected').text().split(" - ")[1].trim();
        $("#multipleServicesTable").append(
          '<tr>' +
            '<td><input type="text" name="service_code[]" value="' + code + '" class="form-control" readonly></td>' +
            '<td><input type="text" name="service_description[]" value="' + description + '" class="form-control" readonly></td>' +
            '<td><input type="number" name="service_days[]" value="' + $("#total_days").val() + '" class="form-control"></td>' +
            '<td><a href="javascript:void(0);" class="removeRowService btn btn-oval btn-danger">Quitar</a></td>' +
          '</tr>'
        );
    });
    $("#multipleServicesTable").on('click',' .removeRowService', function() {
        $(this).parent().parent().remove();
    });
    $("#dynamic-multiple-services").on('change', '#multiple_services', function (e) {
        if ($('#multiple_services').val() == 0) {
          $('#addRowService').removeClass('btn-success');
          $('#addRowService').addClass('btn-secondary');
          $('#multipleServicesDiv').css('display', 'none');
        } else {
          $('#multipleServicesDiv').css('display', 'block');
          $('#multipleServicesDiv').addClass('animated fadeIn');
          $('#addRowService').removeClass('btn-secondary');
          $('#addRowService').addClass('btn-success');
        }
    });
} );
function sendInfo(id, eps_id, name) {
    $('#patient_id').val(id);
    $('#selected_patient').html(name);
    //$('#serviceLink').attr("href", "/eps-services/" + eps_id + "/create-from-authorization");
    $('#serviceLink').attr("href", "javascript:showModal('new-service/" + eps_id + "')");
    $('#restOfFields').css('display', 'block');
    $('#restOfFields').addClass('animated fadeIn');

    document.querySelector('#authFields').scrollIntoView({
        behavior: 'smooth',
        block: 'start'
    });
    $('#epsSelect').val(eps_id);
    fillServices($('#epsSelect').val());
    fillMultipleServices($('#epsSelect').val());
    fillDailyPrices($('#epsSelect').val());
    if (code != '' || description != '') {
        var totalRows = $("#multipleServicesTable").find('tr').length;
        if (totalRows > 1) {
            $("#multipleServicesTable").find('tr').each(function(index, element) {
                if (index > 0) {
                    element.remove();
                }
            });
        }
    }
    // fillPatients($('#epsSelect').val());
    // fillCompanionServices($('#epsSelect').val());
}

function updateInfo(id, name) {
    $('#patient_id').val(id);
    $('#selected_patient').html(name);
    $('#myForm').submit();
}

function validateFormService(myUrl, myFormName, epsId) {
    var values = {};
    $.each($(myFormName).serializeArray(), function(i, field) {
        values[field.name] = field.value;
    });

    $.ajax({
        method: "POST",
        headers: { "X-CSRF-TOKEN" : $("#_tokenBase").val() },
        cache: false,
        url: myUrl,
        data: values,

        success: function(response) {
            $('#modal-success').html('<h3>Guardado...</h3>')
            $('#modal-success').addClass('animated bounce pb-3');
            fillServices(epsId);
            fillMultipleServices(epsId);
            fillDailyPrices(epsId);
            setTimeout(function () { $('#show-modal').modal('hide'); }, 1500);
        },
        error: function(errors){
            $.each(jQuery.parseJSON(errors.responseText), function (index, value) {
                if (index == 'errors') {
                    var messages = '';
                    $('#modal-error').html(messages);
                    $('#modal-error').removeClass('animated fadeInDown pb-3');
                    $.each(value, function(errorIndex, errorValue) {
                        messages += errorValue + "<br>";
                    });
                    $('#modal-error').html(messages);
                    $('#modal-error').addClass('animated fadeInDown pb-3');
                }
            });
        }
    });
}
