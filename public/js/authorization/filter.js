$(document).ready(function() {
    $('#searching').on('change', function (e) {
        fullAuthorizations($('#searching').val());
    });
} );
