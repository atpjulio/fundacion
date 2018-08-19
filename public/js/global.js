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
