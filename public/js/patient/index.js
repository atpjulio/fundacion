$(document).ready(function() {
    $('#searching').on('change', function (e) {
        if ($('#searching').val().length < 1) {
            window.location.href = '/patient';
        } else {
            $('#search-spinner').css('display', 'inline');
            fillFilteredPatients($('#searching').val());
            $('#search-spinner').css('display', 'none');          
        }
    });
} );
