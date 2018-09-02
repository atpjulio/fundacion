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

function fillInvoicesByEpsAndDate(id)
{
    $.get("/get-invoices-amount/" + id, function (data, status) {
        $('#dynamic-invoice-amount').html(data);
    });
}
