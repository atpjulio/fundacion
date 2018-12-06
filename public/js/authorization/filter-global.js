$(document).ready(function() {
    $('#searching').on('change', function (e) {
        globalAuthorizations($('#searching').val());
    });
} );
