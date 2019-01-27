$(document).ready(function() {
    $('#searching').on('change', function (e) {
        if ($('#searching').val().length > 0) {
            fillFilteredReceipts($('#searching').val());
        }
    });
} );
