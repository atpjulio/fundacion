$(document).ready(function() {
    $('#searching').on('change', function (e) {
        if ($('#searching').val().length > 0) {
            fillFilteredEgresses($('#searching').val());
        }
    });
} );
