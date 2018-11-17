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
        $('#searching').on('change', function (e) {
            fillFilteredEpsPatients($('#searching').val());
        });
    });
}

function fillFilteredPatients(search)
{
    $.get("/get-patients/" + search, function (data, status) {
        $('#dynamic-patients').html(data);
        $('#searching').on('change', function (e) {
            fillFilteredPatients($('#searching').val());
        });
    });
}

function fillInvoicesByEpsAndDate(id)
{
    $.get("/get-invoices-amount/" + id, function (data, status) {
        $('#dynamic-invoice-amount').html(data);
    });
}

function showModal(url) {
    $.get('/' + url, function( data ) {
        // console.log(data);
        $("#show-modal-body").html(data);
        $('#show-modal').modal('show');
    });
}

function fillEntityFields(id)
{
    $.get("/get-entity/" + id, function (data, status) {
        $('#dynamic-entity-fields').html(data);
    });
}
