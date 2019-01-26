function fillDays(id)
{
    $.get("/get-day-range/" + id, function (data, status) {
        $('#dynamic-days').html(data);
    });
}

function fillServices(id)
{
    $.get("/get-services/" + id, function (data, status) {
        $('#dynamic-services').html(data);
    });
}

function fillMultipleServices(id)
{
    $.get("/get-multiple-services/" + id, function (data, status) {
        $('#dynamic-multiple-services').html(data);
    });
}

function fillDailyPrices(id)
{
  $.get("/get-daily-prices/" + id, function (data, status) {
    $('#dynamic-daily-prices').html(data);
  });
}

function fillCompanionServices(id)
{
    $.get("/get-companion-services/" + id, function (data, status) {
        $('#dynamic-companion-services').html(data);
        $('#companion_eps_service_id').on('change', function(e) {
            console.log($(this).val());
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
    });
}

function fillCities(id)
{
    $.get("/get-cities/" + id, function (data, status) {
        $('#dynamic-cities').html(data);
    });
}

function fillPatients(id)
{
    $.get("/get-eps-patients/" + id, function (data, status) {
        $('#dynamic-patients').html(data);
        $('#myTable').DataTable({
            "language": {
                "lengthMenu": "Mostrando _MENU_ registros por página",
                "zeroRecords": "No se encontró ningún resultado",
                "info": "Mostrando página _PAGE_ de _PAGES_",
                "infoEmpty": "No hay información disponible",
                "infoFiltered": "(filtrando de un total de _MAX_ registros)",
                "search":         "Buscar:",
                "paginate": {
                    "first":      "Primera",
                    "last":       "Última",
                    "next":       "Siguiente",
                    "previous":   "Anterior"
                }
            }
        });
    });
}

function fillFilteredEpsPatients(search)
{
    $.get("/get-eps-patients-filtered/" + search, function (data, status) {
        $('#dynamic-patients').html(data);
        $('#searching').val(search);
    });
}

function fillFilteredPatients(search)
{
    $.get("/get-patients/" + search, function (data, status) {
        $('#dynamic-patients').html(data);
        $('#searching').val(search);
    });
}

function fillInvoicesByEpsAndDate(id)
{
    $.get("/get-invoices-amount/" + id, function (data, status) {
        $('#dynamic-invoice-amount').html(data);
    });
}

function fillInvoicesByEpsAndNumber(id)
{
    $.get("/get-invoices-amount-number/" + id, function (data, status) {
        $('#dynamic-invoice-amount').html(data);
    });
}

function fillFilteredInvoices(search)
{
    $.get("/get-invoices/" + search, function (data, status) {
        $('#dynamic-invoices').html(data);
    });
}

function showModal(url) {
    $.get('/' + url, function( data ) {
        // console.log(data);
        $("#show-modal-body").html(data);
        $('#show-modal').modal('show');
    });
}

function validateForm(myUrl, myFormName, returnUrl) {
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
            if (returnUrl=="closeModal()") {
                $('#show-modal').modal('hide');
            } else if(returnUrl=="showResponseWithClosingModal") {
                $('#show-modal').modal('hide');
            } else {
                window.location = returnUrl;
            }
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

function fillEntityFields(id)
{
    $.get("/get-entity/" + id, function (data, status) {
        $('#dynamic-entity-fields').html(data);
    });
}

function fullAuthorizations(search)
{
    $.get("/get-full-authorizations/" + search, function (data, status) {
        $('#dynamic-authorizations').html(data);
        $('#searching').val(search);
    });
}

function globalAuthorizations(search)
{
    $.get("/get-global-authorizations/" + search, function (data, status) {
        $('#dynamic-authorizations').html(data);
        $('#searching').val(search);
        $('#searching').on('change', function (e) {
            globalAuthorizations($('#searching').val());
        });
    });
}

function checkPatient(dni)
{
    $.get("/check-patient/" + dni, function (data, status) {
        $('#check-patient').html("");
        if (data.exists) {
            $('#check-patient').html("<em>Documento ya existe en el sistema</em>");
        }
    });
}

function checkAuthorization(code)
{
    $.get("/check-authorization/" + code, function (data, status) {
        $('#check-authorization').html("");
        if (data.exists) {
            $('#check-authorization').html("<em>Autorización ya existe en el sistema</em>");
        }
    });
}

function validateAuthorizationServices(myUrl, myFormName, invoiceId) 
{
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
            // console.log(response);
            $('#modal-success').html('<h3>Actualizando...</h3>')
            $('#modal-success').addClass('animated bounce pb-3');
            // if (invoiceId > 0) {
                fillInvoiceAuthorizations(invoiceId);
            // } else {
            //     fillNewInvoiceAuthorizations(response);
            // }
            setTimeout(function () { $('#show-modal').modal('hide'); }, 1500);
        },
        error: function(errors){
            console.log(errors);
            $('#modal-error').addClass('animated fadeInDown pb-3');
            $('#modal-error').html('<h3>Error desconocido</h3>');
        }
    });
}

function fillInvoiceAuthorizations(invoiceId)
{
    $.get("/get-invoice-authorizations/" + invoiceId, function (data, status) {
        $('#multiple_card').html(data);
        $('#myTable').on('click','.btn-success', function() {
            days = parseInt($(this).parent().parent().find('td')[5].outerText);
            if ($('#multiple').is(":checked")) {
                // console.log($("#multiple_table tr").length);
                // console.log($("#multiple_table tr:nth-child(1)").find('td input')[0].value.length);
                if ($("#multiple_table tr:nth-child(1)").find('td input')[0].value.length > 0) {
                    $("#multiple_table").append(
                        '<tr><td><input type="text" id="multiple_codes" name="multiple_codes[]" value="' + $(this).parent().parent().find('td').first()[0].outerText.trim() + '" class="form-control" placeholder="Número de autorización" readonly />'
                        + '</td><td><input type="number" id="multiple_days" name="multiple_days[]" value="' + days + '" class="form-control multipleDays" placeholder="Total de días" min="0"/>'
                        + '</td><td><input type="number" id="multiple_totals" name="multiple_totals[]" value="' + ($(this).parent().find('input')[0].value * days) + '" class="form-control" placeholder="Valor total" min="0"/>'
                        + '<input type="hidden" id="multiple_price" name="multiple_price[]" class="form-control" placeholder="" min="0" value="' + $(this).parent().find('input')[0].value + '" />'
                        + '</td><td><a href="javascript:void(0);" class="removeRow btn btn-oval btn-danger">Quitar</a>&nbsp;<a href="javascript:void(0);" class="servicesDetail btn btn-oval btn-secondary">Servicios</a></td></tr>'
                    );
                } else {
                    $("#multiple_table tr:last").remove();
                    $("#multiple_table").append(
                        '<tr><td><input type="text" id="multiple_codes" name="multiple_codes[]" value="' + $(this).parent().parent().find('td').first()[0].outerText.trim() + '" class="form-control" placeholder="Número de autorización" readonly />'
                        + '</td><td><input type="number" id="multiple_days" name="multiple_days[]" value="' + days + '" class="form-control multipleDays" placeholder="Total de días" min="0"/>'
                        + '</td><td><input type="number" id="multiple_totals" name="multiple_totals[]" value="' + ($(this).parent().find('input')[0].value * days) + '" class="form-control" placeholder="Valor total" min="0"/>'
                        + '<input type="hidden" id="multiple_price" name="multiple_price[]" class="form-control" placeholder="" min="0" value="' + $(this).parent().find('input')[0].value + '" />'
                        + '</td><td><a href="javascript:void(0);" class="removeRow btn btn-oval btn-danger">Quitar</a>&nbsp;<a href="javascript:void(0);" class="servicesDetail btn btn-oval btn-secondary">Servicios</a></td></tr>'
                    );
                }

                $('#alertTable').css('display', 'none');
                $('#tableMessage').html('');

                document.querySelector('#multiple_table').scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
                history.pushState(null, null, '#multiple_table');
                e.preventDefault();
            } else {
                $('#total_days').val(days);
                $('#selected_price').val($(this).parent().find('input')[0].value);
                $('#total').val($('#selected_price').val() * $('#total_days').val());
                //$('#total').val($('#selected_price').val() * $('#total_days').val() + parseInt($('#total').val()));
                $('#authorization_code').val($(this).parent().parent().find('td').first()[0].outerText.trim());
                //$('#authorization_code').val($(this).parent().parent().find('td').first()[0].outerText.trim() + "," + $('#authorization_code').val());

                $('html, body').animate({
                    scrollTop: $('#beginning').offset().top
                }, 300, function(){
                    window.location.href = '#beginning';
                });
            }
        });
        $('#total_days').on('change keyup', function (e) {
            $('#total').val($('#selected_price').val() * $('#total_days').val());
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
        $('#multiple_table').on('change', '.multipleDays', function (e) {
            $('#selected_price').val($(this).parent().parent().find('td input')[3].value);
            $(this).parent().parent().find('td input')[2].value = e.target.value * $('#selected_price').val();
        });
        $("#multiple_table").on('click','.addRow', function() {
            if ($('#multiple_codes').val().length > 0 && $('#multiple_days').val() > 0 && $('#multiple_totals').val() > 0) {
                $("#multiple_table").append(
                    '<tr><td><input type="text" id="multiple_codes" name="multiple_codes[]" class="form-control" placeholder="Número de autorización" value="" readonly />'
                    + '</td><td><input type="number" id="multiple_days" name="multiple_days[]" class="form-control multipleDays" placeholder="Total de días" min="0" value=""/>'
                    + '</td><td><input type="number" id="multiple_totals" name="multiple_totals[]" class="form-control" placeholder="Valor total" min="0" value=""/><input type="hidden" id="multiple_price" name="multiple_price[]" class="form-control" placeholder="" min="0" value="" />'
                    + '</td><td><a href="javascript:void(0);" class="removeRow btn btn-oval btn-danger">Quitar</a>&nbsp;<a href="javascript:void(0);" class="servicesDetail btn btn-oval btn-secondary">Servicios</a></td></tr>'
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
        $("#multiple_table").on('click','.servicesDetail', function() {
            var currentAuthorizationCode = $(this).parent().parent().find('td input')[0].value;
            showModal('get-authorization-services/' + currentAuthorizationCode);
        });

    });
}

function fillNewInvoiceAuthorizations(invoice)
{
    var values = JSON.parse(invoice);

    $.ajax({
        method: "POST",
        headers: { "X-CSRF-TOKEN" : $("#_tokenBase").val() },
        cache: false,
        url: '/update-new-invoice',
        data: values,

        success: function(response) {  
            $('#multiple_card').html(response);
            $('#myTable').on('click','.btn-success', function() {
                days = parseInt($(this).parent().parent().find('td')[5].outerText);
                if ($('#multiple').is(":checked")) {
                    // console.log($("#multiple_table tr").length);
                    // console.log($("#multiple_table tr:nth-child(1)").find('td input')[0].value.length);
                    if ($("#multiple_table tr:nth-child(1)").find('td input')[0].value.length > 0) {
                        $("#multiple_table").append(
                            '<tr><td><input type="text" id="multiple_codes" name="multiple_codes[]" value="' + $(this).parent().parent().find('td').first()[0].outerText.trim() + '" class="form-control" placeholder="Número de autorización" readonly />'
                            + '</td><td><input type="number" id="multiple_days" name="multiple_days[]" value="' + days + '" class="form-control multipleDays" placeholder="Total de días" min="0"/>'
                            + '</td><td><input type="number" id="multiple_totals" name="multiple_totals[]" value="' + ($(this).parent().find('input')[0].value * days) + '" class="form-control" placeholder="Valor total" min="0"/>'
                            + '<input type="hidden" id="multiple_price" name="multiple_price[]" class="form-control" placeholder="" min="0" value="' + $(this).parent().find('input')[0].value + '" />'
                            + '</td><td><a href="javascript:void(0);" class="removeRow btn btn-oval btn-danger">Quitar</a>&nbsp;<a href="javascript:void(0);" class="servicesDetail btn btn-oval btn-secondary">Servicios</a></td></tr>'
                        );
                    } else {
                        $("#multiple_table tr:last").remove();
                        $("#multiple_table").append(
                            '<tr><td><input type="text" id="multiple_codes" name="multiple_codes[]" value="' + $(this).parent().parent().find('td').first()[0].outerText.trim() + '" class="form-control" placeholder="Número de autorización" readonly />'
                            + '</td><td><input type="number" id="multiple_days" name="multiple_days[]" value="' + days + '" class="form-control multipleDays" placeholder="Total de días" min="0"/>'
                            + '</td><td><input type="number" id="multiple_totals" name="multiple_totals[]" value="' + ($(this).parent().find('input')[0].value * days) + '" class="form-control" placeholder="Valor total" min="0"/>'
                            + '<input type="hidden" id="multiple_price" name="multiple_price[]" class="form-control" placeholder="" min="0" value="' + $(this).parent().find('input')[0].value + '" />'
                            + '</td><td><a href="javascript:void(0);" class="removeRow btn btn-oval btn-danger">Quitar</a>&nbsp;<a href="javascript:void(0);" class="servicesDetail btn btn-oval btn-secondary">Servicios</a></td></tr>'
                        );
                    }
    
                    $('#alertTable').css('display', 'none');
                    $('#tableMessage').html('');
    
                    document.querySelector('#multiple_table').scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                    history.pushState(null, null, '#multiple_table');
                    e.preventDefault();
                } else {
                    $('#total_days').val(days);
                    $('#selected_price').val($(this).parent().find('input')[0].value);
                    $('#total').val($('#selected_price').val() * $('#total_days').val());
                    //$('#total').val($('#selected_price').val() * $('#total_days').val() + parseInt($('#total').val()));
                    $('#authorization_code').val($(this).parent().parent().find('td').first()[0].outerText.trim());
                    //$('#authorization_code').val($(this).parent().parent().find('td').first()[0].outerText.trim() + "," + $('#authorization_code').val());
    
                    $('html, body').animate({
                        scrollTop: $('#beginning').offset().top
                    }, 300, function(){
                        window.location.href = '#beginning';
                    });
                }
            });
            $('#total_days').on('change keyup', function (e) {
                $('#total').val($('#selected_price').val() * $('#total_days').val());
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
            $('#multiple_table').on('change', '.multipleDays', function (e) {
                $('#selected_price').val($(this).parent().parent().find('td input')[3].value);
                $(this).parent().parent().find('td input')[2].value = e.target.value * $('#selected_price').val();
            });
            $("#multiple_table").on('click','.addRow', function() {
                if ($('#multiple_codes').val().length > 0 && $('#multiple_days').val() > 0 && $('#multiple_totals').val() > 0) {
                    $("#multiple_table").append(
                        '<tr><td><input type="text" id="multiple_codes" name="multiple_codes[]" class="form-control" placeholder="Número de autorización" value="" readonly />'
                        + '</td><td><input type="number" id="multiple_days" name="multiple_days[]" class="form-control multipleDays" placeholder="Total de días" min="0" value=""/>'
                        + '</td><td><input type="number" id="multiple_totals" name="multiple_totals[]" class="form-control" placeholder="Valor total" min="0" value=""/><input type="hidden" id="multiple_price" name="multiple_price[]" class="form-control" placeholder="" min="0" value="" />'
                        + '</td><td><a href="javascript:void(0);" class="removeRow btn btn-oval btn-danger">Quitar</a>&nbsp;<a href="javascript:void(0);" class="servicesDetail btn btn-oval btn-secondary">Servicios</a></td></tr>'
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
            $("#multiple_table").on('click','.servicesDetail', function() {
                var currentAuthorizationCode = $(this).parent().parent().find('td input')[0].value;
                showModal('get-authorization-services/' + currentAuthorizationCode);
            });
        },
        error: function(errors){
            console.log(errors);
        }
    });
}

function fillFilteredEgresses(search)
{
    $.get("/get-egresses-filtered/" + search, function (data, status) {
        $('#dynamic-egresses').html(data);
        $('#searching').val(search);
    });
}

