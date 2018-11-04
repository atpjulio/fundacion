$(document).ready(function() {
    $('#myTable').DataTable({
        "language": {
            "lengthMenu": "Mostrando _MENU_ registros por página",
            "zeroRecords": "No se encontró ningún resultado",
            "info": "Mostrando página _PAGE_ de _PAGES_",
            "infoEmpty": "No hay información disponible",
            "infoFiltered": "(filtrando de un total de _MAX_ registros)",
            "search":         "Buscar:",
            "paginate": {
                "first":      "Primera",
                "last":       "Última",
                "next":       "Siguiente",
                "previous":   "Anterior"
            }
        }
    });
    $('#companion').on('change', function (e) {
        if ($('#companion').val() == 1) {
            $('#companionsDiv').css('display', 'block');
            $('#companionsDiv').addClass('animated fadeIn');
        } else {
            $('#companionsDiv').css('display', 'none');
        }
    });
    $('#epsSelect').on('change', function (e) {
        $('#serviceLink').attr("href", "/eps-services/" + $('#epsSelect').val() + "/create-from-authorization");
        fillServices($('#epsSelect').val());
        fillPatients($('#epsSelect').val());
        fillCompanionServices($('#epsSelect').val());
    });
    $('#companion_eps_service_id').on('change', function(e) {
        if ($(this).val() > 0) {             
            $('#companion_service').val($(this).children('option').filter(":selected").text());
            $('#companion_service_id').val($(this).val());
            $('#alertTable').css('display', 'none');
            $('#tableMessage').html('');              

        } else {
            $('#companion_service').val('');
            $('#companion_service_id').val('');
            $('#tableMessage').html('Por favor seleccione un servicio válido');              
            $('#alertTable').css('display', 'block');
        }
    });
    $(".addRow").click( function() {

        if ($('#companion_service').val().length > 0 && $('#companion_service_id').val().length > 0 && $('#companion_document').val().length > 0) {
            $("#companionsTable").append('<tr><td><input type="text" id="companionDni" name="companionDni[]" value="' + $('#companion_document').val() + '" class="form-control" placeholder="Número de Documento"/></td><td><input type="text" id="companionService" value="' + $('#companion_service').val() + '" name="companionService[]" class="form-control" placeholder="Servicio para el acompañante" readonly /><input type="hidden" name="companionServiceId[]" id="companionServiceId" value="' + $('#companion_service_id').val() + '"></td><td><a href="javascript:void(0);" class="removeRow btn btn-oval btn-danger">Quitar</a></td><tr>');

            $('#companion_document').val('');
            $('#companion_service').val('');
            $('#companion_service_id').val('');                    
            $('#alertTable').css('display', 'none');
            $('#tableMessage').html('');              
        } else {
            if ($('#companion_service').val().length == 0) {
                $('#tableMessage').html('Por favor seleccione un servicio válido');              
                $('#alertTable').css('display', 'block');

            } else {
                $('#tableMessage').html('Por favor ingrese un número de documento');              
                $('#alertTable').css('display', 'block');                        
            }
        }

    });
    $("#companionsTable").on('click','.removeRow', function() {
        $(this).parent().parent().remove();
    });

} );
function sendInfo(id, eps_id) {
    $('#patient_id').val(id);
    // $('#myForm').submit();
    $('#serviceLink').attr("href", "/eps-services/" + eps_id + "/create-from-authorization");
    $('#restOfFields').css('display', 'block');            
    $('#restOfFields').addClass('animated fadeIn');
    $('html, body').animate({
            scrollTop: $('#authFields').offset().top
        }, 300, function(){
            window.location.href = '#authFields';
        });
    $('#epsSelect').val(eps_id);
    fillServices($('#epsSelect').val());
    fillPatients($('#epsSelect').val());
    fillCompanionServices($('#epsSelect').val());
}