function fillDays(id)
{
    $.get("/get-day-range/" + id, function (data, status) {
        $('#dynamic-days').html(data);
    });
}
