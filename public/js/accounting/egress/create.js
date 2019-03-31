$(document).ready(function() {
    $('#entity_id').on('change', function (e) {
        if ($('#entity_id').val() != "0") {            
            fillEntityFields($('#entity_id').val());
        } else {
            $('#name').val('');
            $('#doc').val('');
            $('#address').val('');
            $('#phone').val('');
        }
    });
} );
