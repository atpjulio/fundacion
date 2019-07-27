$(document).ready(function() {
    $('.custom-file-input').on('change', function () {
        $(this).next('.form-control-file').addClass("selected").html($(this)[0].files[0].name);
    });
    $('#state').on('change', function (e) {
        fillCities($('#state').val());
    });
});
