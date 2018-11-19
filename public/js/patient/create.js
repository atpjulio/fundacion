$(document).ready(function() {
    $('#birth_year').on('change', function (e) {
        fillDays($('#birth_year').val() + "-" + $('#birth_month').val());
    });

    $('#birth_month').on('change', function (e) {
        fillDays($('#birth_year').val() + "-" + $('#birth_month').val());
    });

    $('#state').on('change', function (e) {
        fillCities($('#state').val());
    });

    $('#dni').on('change keyup', function (e) {
        if ($('#dni').val().length > 4) {
            checkPatient($('#dni').val());
        }
    });
});
