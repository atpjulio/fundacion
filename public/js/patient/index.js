$(document).ready(function() {
    $('#searching').on('change', function (e) {
        fillFilteredPatients($('#searching').val());
    });
} );
